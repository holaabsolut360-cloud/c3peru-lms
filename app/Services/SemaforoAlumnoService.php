<?php

namespace App\Services;

use App\Models\ActividadAlumno;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\ExamenIntento;
use App\Models\Leccion;
use App\Models\Matricula;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Carbon;

class SemaforoAlumnoService
{
    /**
     * @param  EloquentCollection<int, Matricula>  $matriculas
     * @return array<int, array{estado: string, progreso: float, dias_sin_actividad: int, ultima_actividad: Carbon|null, mensaje: string, reprobo_examen: bool}>
     */
    public function calcularEstados(EloquentCollection $matriculas): array
    {
        if ($matriculas->isEmpty()) {
            return [];
        }

        $userIds = $matriculas->pluck('user_id')->unique()->values();
        $cursoIds = $matriculas->pluck('curso_id')->unique()->values();
        $clave = static fn (int|string $userId, int|string $cursoId): string => $userId.':'.$cursoId;

        $leccionesTotales = Leccion::query()
            ->whereIn('curso_id', $cursoIds)
            ->selectRaw('curso_id, COUNT(*) AS total')
            ->groupBy('curso_id')
            ->pluck('total', 'curso_id');

        $leccionesCompletadas = ActividadAlumno::query()
            ->whereIn('user_id', $userIds)
            ->whereIn('curso_id', $cursoIds)
            ->where('tipo', ActividadAlumno::TIPO_LECCION_COMPLETADA)
            ->selectRaw('user_id, curso_id, COUNT(DISTINCT leccion_id) AS total')
            ->groupBy('user_id', 'curso_id')
            ->get()
            ->mapWithKeys(fn (ActividadAlumno $actividad): array => [
                $clave($actividad->user_id, $actividad->curso_id) => (int) $actividad->total,
            ]);

        $ultimasActividades = ActividadAlumno::query()
            ->whereIn('user_id', $userIds)
            ->whereIn('curso_id', $cursoIds)
            ->selectRaw('user_id, curso_id, MAX(created_at) AS ultima_actividad')
            ->groupBy('user_id', 'curso_id')
            ->get()
            ->mapWithKeys(fn (ActividadAlumno $actividad): array => [
                $clave($actividad->user_id, $actividad->curso_id) => $actividad->ultima_actividad,
            ]);

        $cursosPorExamen = Examen::query()
            ->whereIn('curso_id', $cursoIds)
            ->pluck('curso_id', 'id');

        $examenesReprobados = collect();
        if ($cursosPorExamen->isNotEmpty()) {
            $examenesReprobados = ExamenIntento::query()
                ->whereIn('user_id', $userIds)
                ->whereIn('examen_id', $cursosPorExamen->keys())
                ->where('aprobado', false)
                ->distinct()
                ->get(['user_id', 'examen_id'])
                ->mapWithKeys(fn (ExamenIntento $intento): array => [
                    $clave($intento->user_id, $cursosPorExamen->get($intento->examen_id)) => true,
                ]);
        }

        $estados = [];
        foreach ($matriculas as $matricula) {
            $matriculaKey = $clave($matricula->user_id, $matricula->curso_id);
            $totalLecciones = (int) $leccionesTotales->get($matricula->curso_id, 0);
            $leccionesHechas = (int) $leccionesCompletadas->get($matriculaKey, 0);
            $progreso = $totalLecciones > 0
                ? round(min(100, ($leccionesHechas / $totalLecciones) * 100), 2)
                : 0.0;

            $ultimaActividad = $ultimasActividades->get($matriculaKey);
            $ultimaActividad = $ultimaActividad ? Carbon::parse($ultimaActividad) : null;
            $fechaReferencia = $ultimaActividad ?? $matricula->created_at;
            $diasSinActividad = $fechaReferencia ? (int) $fechaReferencia->diffInDays(now()) : 999;
            $reproboExamen = (bool) $examenesReprobados->has($matriculaKey);

            if ($reproboExamen) {
                $estado = 'rojo';
                $mensaje = 'Requiere apoyo en una evaluación';
            } elseif ($progreso >= 70 && $diasSinActividad <= 7) {
                $estado = 'verde';
                $mensaje = 'Avanza a buen ritmo';
            } elseif ($diasSinActividad > 14) {
                $estado = 'rojo';
                $mensaje = 'Más de 14 días sin actividad';
            } else {
                $estado = 'amarillo';
                $mensaje = $ultimaActividad ? 'Requiere seguimiento' : 'Aún no registra actividad';
            }

            $estados[$matricula->id] = [
                'estado' => $estado,
                'progreso' => $progreso,
                'dias_sin_actividad' => $diasSinActividad,
                'ultima_actividad' => $ultimaActividad,
                'mensaje' => $mensaje,
                'reprobo_examen' => $reproboExamen,
            ];
        }

        return $estados;
    }

    /**
     * @return array{estado: string, progreso: float, dias_sin_actividad: int, ultima_actividad: Carbon|null, mensaje: string, reprobo_examen: bool}
     */
    public function calcularEstado(Matricula $matricula): array
    {
        $estados = $this->calcularEstados(new EloquentCollection([$matricula]));

        return $estados[$matricula->id];
    }

    /**
     * @param  array<int, int|string>  $cursoIds
     */
    public function obtenerUltimaActividad(int $userId, array $cursoIds): ?ActividadAlumno
    {
        if ($cursoIds === []) {
            return null;
        }

        return ActividadAlumno::query()
            ->where('user_id', $userId)
            ->whereIn('curso_id', $cursoIds)
            ->whereNotNull('leccion_id')
            ->with(['curso', 'leccion'])
            ->latest('updated_at')
            ->first();
    }

    public function registrarVisitaCurso(int $userId, Curso $curso): void
    {
        $visitaReciente = ActividadAlumno::query()
            ->where('user_id', $userId)
            ->where('curso_id', $curso->id)
            ->where('tipo', ActividadAlumno::TIPO_CURSO_VISITADO)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->exists();

        if (! $visitaReciente) {
            ActividadAlumno::query()->create([
                'user_id' => $userId,
                'curso_id' => $curso->id,
                'tipo' => ActividadAlumno::TIPO_CURSO_VISITADO,
            ]);
        }
    }

    public function registrarVideoVisto(int $userId, int $cursoId, int $leccionId): void
    {
        $vistaReciente = ActividadAlumno::query()
            ->where('user_id', $userId)
            ->where('curso_id', $cursoId)
            ->where('leccion_id', $leccionId)
            ->where('tipo', ActividadAlumno::TIPO_VIDEO_VISTO)
            ->where('created_at', '>=', now()->subMinutes(30))
            ->exists();

        if (! $vistaReciente) {
            ActividadAlumno::query()->create([
                'user_id' => $userId,
                'curso_id' => $cursoId,
                'leccion_id' => $leccionId,
                'tipo' => ActividadAlumno::TIPO_VIDEO_VISTO,
            ]);
        }
    }

    public function registrarProgresoVideo(int $userId, Curso $curso, Leccion $leccion, int $duracionSegundos): void
    {
        $actividad = ActividadAlumno::query()
            ->where('user_id', $userId)
            ->where('curso_id', $curso->id)
            ->where('leccion_id', $leccion->id)
            ->where('tipo', ActividadAlumno::TIPO_VIDEO_VISTO)
            ->latest('updated_at')
            ->first();

        if (! $actividad) {
            ActividadAlumno::query()->create([
                'user_id' => $userId,
                'curso_id' => $curso->id,
                'leccion_id' => $leccion->id,
                'tipo' => ActividadAlumno::TIPO_VIDEO_VISTO,
                'duracion_segundos' => $duracionSegundos,
            ]);

            return;
        }

        $actividad->update(['duracion_segundos' => $duracionSegundos]);
    }

    public function marcarLeccionCompletada(int $userId, Curso $curso, Leccion $leccion): void
    {
        ActividadAlumno::query()->firstOrCreate(
            [
                'user_id' => $userId,
                'curso_id' => $curso->id,
                'leccion_id' => $leccion->id,
                'tipo' => ActividadAlumno::TIPO_LECCION_COMPLETADA,
            ],
            ['porcentaje_completado' => 100],
        );
    }
}
