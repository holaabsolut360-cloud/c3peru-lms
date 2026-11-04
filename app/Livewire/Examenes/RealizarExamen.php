<?php

namespace App\Livewire\Examenes;

use App\Models\ActividadAlumno;
use App\Models\Examen;
use App\Models\ExamenIntento;
use App\Models\ExamenRespuesta;
use App\Models\Matricula;
use App\Models\Pregunta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;
use Livewire\Component;

class RealizarExamen extends Component
{
    #[Locked]
    public int $examenId;

    #[Locked]
    public array $preguntas = [];

    #[Locked]
    public ?int $intentoId = null;

    #[Locked]
    public bool $examenIniciado = false;

    #[Locked]
    public bool $examenFinalizado = false;

    #[Locked]
    public bool $intentosAgotados = false;

    #[Locked]
    public int $tiempoRestante = 0;

    #[Locked]
    public ?float $notaFinal = null;

    #[Locked]
    public ?bool $aprobado = null;

    public array $respuestas = [];

    public function mount(Examen $examen): void
    {
        $this->autorizarAcceso($examen);
        $this->examenId = $examen->id;
        $this->preguntas = $examen->preguntas()
            ->get(['id', 'enunciado', 'opciones', 'puntaje', 'dificultad'])
            ->map(fn (Pregunta $pregunta): array => [
                'id' => $pregunta->id,
                'enunciado' => $pregunta->enunciado,
                'opciones' => $pregunta->opciones,
                'puntaje' => $pregunta->puntaje,
                'dificultad' => $pregunta->dificultad,
            ])
            ->all();

        $intentoActivo = $this->intentoQuery()->whereNull('finalizado_en')->latest('iniciado_en')->first();
        if ($intentoActivo) {
            $this->intentoId = $intentoActivo->id;
            $this->examenIniciado = true;
            $this->respuestas = ExamenRespuesta::query()
                ->where('examen_intento_id', $intentoActivo->id)
                ->whereNotNull('respuesta_seleccionada')
                ->pluck('respuesta_seleccionada', 'pregunta_id')
                ->all();
            $this->actualizarTiempo();

            return;
        }

        $intentosCompletados = $this->intentoQuery()->whereNotNull('finalizado_en')->count();
        $this->intentosAgotados = $intentosCompletados >= $examen->intentos_permitidos;
        if ($this->intentosAgotados) {
            $ultimoIntento = $this->intentoQuery()->whereNotNull('finalizado_en')->latest('finalizado_en')->first();
            $this->examenFinalizado = true;
            $this->notaFinal = $ultimoIntento?->nota_final !== null ? (float) $ultimoIntento->nota_final : null;
            $this->aprobado = $ultimoIntento?->aprobado;
        }
    }

    public function iniciarExamen(): void
    {
        $examen = Examen::query()->findOrFail($this->examenId);
        $this->autorizarAcceso($examen);

        $this->validarDisponibilidad($examen);
        if ($this->preguntas === []) {
            $this->addError('examen', 'Este examen todavía no tiene preguntas.');

            return;
        }

        $intentoActivo = $this->intentoQuery()->whereNull('finalizado_en')->latest('iniciado_en')->first();
        if (! $intentoActivo) {
            $completados = $this->intentoQuery()->whereNotNull('finalizado_en')->count();
            if ($completados >= $examen->intentos_permitidos) {
                $this->intentosAgotados = true;

                return;
            }

            $ahora = now();
            $intentoActivo = $examen->intentos()->create([
                'user_id' => auth()->id(),
                'iniciado_en' => $ahora,
                'expira_en' => $ahora->copy()->addMinutes($examen->duracion_minutos),
            ]);
        }

        $this->intentoId = $intentoActivo->id;
        $this->examenIniciado = true;
        $this->actualizarTiempo();
    }

    public function responder(int $preguntaId, string $opcion): void
    {
        $intento = $this->obtenerIntentoActivo();
        if (! $intento) {
            return;
        }
        if (now()->greaterThanOrEqualTo($intento->expira_en)) {
            $this->finalizarExamen();

            return;
        }

        $pregunta = Pregunta::query()
            ->where('examen_id', $this->examenId)
            ->whereKey($preguntaId)
            ->first();
        if (! $pregunta || ! array_key_exists($opcion, $pregunta->opciones)) {
            $this->addError('respuestas', 'La opción seleccionada no es válida.');

            return;
        }

        ExamenRespuesta::query()->updateOrCreate(
            ['examen_intento_id' => $intento->id, 'pregunta_id' => $pregunta->id],
            ['respuesta_seleccionada' => $opcion, 'es_correcta' => null, 'puntaje_obtenido' => 0],
        );
        $this->respuestas[$pregunta->id] = $opcion;
    }

    public function actualizarTiempo(): void
    {
        if (! $this->intentoId || $this->examenFinalizado) {
            return;
        }

        $intento = $this->intentoQuery()->whereKey($this->intentoId)->first();
        if (! $intento || $intento->finalizado_en) {
            return;
        }

        $this->tiempoRestante = max(0, (int) now()->diffInSeconds($intento->expira_en, false));
        if ($this->tiempoRestante === 0) {
            $this->finalizarExamen();
        }
    }

    public function finalizarExamen(): void
    {
        $intento = $this->obtenerIntentoActivo();
        if (! $intento) {
            return;
        }

        $examen = Examen::query()->findOrFail($this->examenId);
        $resultado = DB::transaction(function () use ($examen, $intento): array {
            $intento = ExamenIntento::query()->whereKey($intento->id)->lockForUpdate()->firstOrFail();
            if ($intento->finalizado_en) {
                return [
                    'nota' => (float) $intento->nota_final,
                    'aprobado' => (bool) $intento->aprobado,
                ];
            }

            $preguntas = $examen->preguntas()->get();
            $respuestasGuardadas = ExamenRespuesta::query()
                ->where('examen_intento_id', $intento->id)
                ->get()
                ->keyBy('pregunta_id');
            $puntajeTotal = 0;
            $puntajeMaximo = (int) $preguntas->sum('puntaje');

            foreach ($preguntas as $pregunta) {
                $respuesta = $respuestasGuardadas->get($pregunta->id);
                $seleccionada = $respuesta?->respuesta_seleccionada;
                $correcta = $seleccionada !== null && hash_equals($pregunta->respuesta_correcta, $seleccionada);
                $puntaje = $correcta ? $pregunta->puntaje : 0;
                $puntajeTotal += $puntaje;

                ExamenRespuesta::query()->updateOrCreate(
                    ['examen_intento_id' => $intento->id, 'pregunta_id' => $pregunta->id],
                    [
                        'respuesta_seleccionada' => $seleccionada,
                        'es_correcta' => $correcta,
                        'puntaje_obtenido' => $puntaje,
                    ],
                );
            }

            $nota = $puntajeMaximo > 0 ? round(($puntajeTotal / $puntajeMaximo) * 20, 2) : 0.0;
            $aprobado = $nota >= (float) $examen->puntaje_aprobatorio;
            $intento->update([
                'finalizado_en' => now(),
                'puntaje_total' => $puntajeTotal,
                'nota_final' => $nota,
                'porcentaje' => $puntajeMaximo > 0 ? (int) round(($puntajeTotal / $puntajeMaximo) * 100) : 0,
                'aprobado' => $aprobado,
            ]);

            ActividadAlumno::query()->create([
                'user_id' => $intento->user_id,
                'curso_id' => $examen->curso_id,
                'tipo' => $aprobado
                    ? ActividadAlumno::TIPO_EXAMEN_APROBADO
                    : ActividadAlumno::TIPO_EXAMEN_REPROBADO,
            ]);

            return ['nota' => $nota, 'aprobado' => $aprobado];
        });

        $this->examenFinalizado = true;
        $this->examenIniciado = false;
        $this->tiempoRestante = 0;
        $this->notaFinal = $resultado['nota'];
        $this->aprobado = $resultado['aprobado'];
        $this->intentosAgotados = $this->intentoQuery()->whereNotNull('finalizado_en')->count() >= $examen->intentos_permitidos;
    }

    public function render()
    {
        $examen = Examen::query()->findOrFail($this->examenId);

        return view('livewire.examenes.realizar-examen', ['examen' => $examen]);
    }

    private function autorizarAcceso(Examen $examen): void
    {
        abort_unless(Matricula::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $examen->curso_id)
            ->where('estado', 'activa')
            ->exists(), 403);
        $this->validarDisponibilidad($examen);
    }

    private function validarDisponibilidad(Examen $examen): void
    {
        abort_unless($examen->disponible, 404);
        abort_if($examen->fecha_inicio && now()->lt($examen->fecha_inicio), 404);
        abort_if($examen->fecha_fin && now()->gt($examen->fecha_fin), 404);
    }

    private function obtenerIntentoActivo(): ?ExamenIntento
    {
        if (! $this->intentoId || ! $this->examenIniciado || $this->examenFinalizado) {
            return null;
        }

        $intento = ExamenIntento::query()
            ->whereKey($this->intentoId)
            ->where('examen_id', $this->examenId)
            ->where('user_id', auth()->id())
            ->whereNull('finalizado_en')
            ->first();

        if (! $intento) {
            $this->examenIniciado = false;

            return null;
        }

        return $intento;
    }

    private function intentoQuery(): Builder
    {
        return ExamenIntento::query()
            ->where('examen_id', $this->examenId)
            ->where('user_id', auth()->id());
    }
}
