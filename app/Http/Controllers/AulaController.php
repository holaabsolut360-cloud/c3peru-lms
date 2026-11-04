<?php

namespace App\Http\Controllers;

use App\Models\ActividadAlumno;
use App\Models\ClaseEnVivo;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\InsigniaUsuario;
use App\Models\Leccion;
use App\Models\LeccionMaterial;
use App\Models\Matricula;
use App\Models\Pago;
use App\Services\OpenBadgesService;
use App\Services\SemaforoAlumnoService;
use App\Services\VideoProtectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AulaController extends Controller
{
    public function inicio(OpenBadgesService $openBadges, SemaforoAlumnoService $semaforo): View
    {
        $userId = (int) auth()->id();
        if (auth()->user()->role === 'alumno') {
            $openBadges->emitirInsigniasPorExamenesAprobados($userId);
        }

        $matriculas = Matricula::query()
            ->with('curso.categoria')
            ->where('user_id', auth()->id())
            ->where('estado', 'activa')
            ->latest()
            ->get();
        $proximasClases = ClaseEnVivo::query()
            ->with(['curso', 'docente', 'leccion'])
            ->whereIn('curso_id', $matriculas->pluck('curso_id'))
            ->proximas()
            ->limit(5)
            ->get();
        $ultimaActividad = $semaforo->obtenerUltimaActividad(
            $userId,
            $matriculas->pluck('curso_id')->map(fn ($cursoId): int => (int) $cursoId)->all(),
        );

        return view('aula.inicio', [
            'matriculas' => $matriculas,
            'proximasClases' => $proximasClases,
            'ultimaActividad' => $ultimaActividad,
            'insignias' => InsigniaUsuario::query()
                ->with('insignia')
                ->where('user_id', $userId)
                ->latest('otorgada_en')
                ->get(),
        ]);
    }

    public function curso(Curso $curso, VideoProtectionService $proteccionVideo, SemaforoAlumnoService $semaforo): View
    {
        $matricula = Matricula::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->first();
        abort_unless($matricula, 403);
        $semaforo->registrarVisitaCurso((int) auth()->id(), $curso);

        $lecciones = $curso->lecciones()->with(['videos', 'materiales'])->orderBy('orden')->get();
        $leccionesCompletadas = ActividadAlumno::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $curso->id)
            ->where('tipo', ActividadAlumno::TIPO_LECCION_COMPLETADA)
            ->pluck('leccion_id');
        $usuario = auth()->user();
        $videosPorLeccion = $lecciones->mapWithKeys(function ($leccion) use ($proteccionVideo, $usuario): array {
            return [
                $leccion->id => $leccion->videos->map(function ($video) use ($proteccionVideo, $usuario): array {
                    return [
                        'video' => $video,
                        'url' => $proteccionVideo->generarUrlFirmada($video, $usuario),
                    ];
                }),
            ];
        });
        $progresoVideoReciente = ActividadAlumno::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $curso->id)
            ->where('tipo', ActividadAlumno::TIPO_VIDEO_VISTO)
            ->whereNotNull('duracion_segundos')
            ->latest('updated_at')
            ->first();

        return view('aula.curso', [
            'curso' => $curso,
            'lecciones' => $lecciones,
            'leccionesCompletadas' => $leccionesCompletadas,
            'videosPorLeccion' => $videosPorLeccion,
            'progresoVideoReciente' => $progresoVideoReciente,
            'clases' => ClaseEnVivo::query()
                ->with(['curso', 'docente', 'leccion'])
                ->where('curso_id', $curso->id)
                ->proximas()
                ->get(),
            'examenes' => $curso->examenes()
                ->where('disponible', true)
                ->where(fn ($consulta) => $consulta->whereNull('fecha_inicio')->orWhere('fecha_inicio', '<=', now()))
                ->where(fn ($consulta) => $consulta->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', now()))
                ->withCount('preguntas')
                ->orderBy('titulo')
                ->get(),
            'tareas' => $curso->tareas()
                ->where('publicada', true)
                ->with([
                    'rubricas',
                    'entregas' => fn ($consulta) => $consulta
                        ->where('user_id', auth()->id())
                        ->with('calificaciones.rubrica')
                        ->latest('entregado_en'),
                ])
                ->latest()
                ->get(),
            'anuncios' => $curso->anuncios()
                ->whereNotNull('publicado_en')
                ->latest('publicado_en')
                ->get(),
        ]);
    }

    public function guardarProgresoVideo(Request $request, Curso $curso, Leccion $leccion, SemaforoAlumnoService $semaforo): JsonResponse
    {
        abort_unless((int) $leccion->curso_id === (int) $curso->id, 404);
        abort_unless($request->user()->role === 'alumno', 403);
        abort_unless(Matricula::query()
            ->where('user_id', $request->user()->id)
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->exists(), 403);

        $datos = $request->validate([
            'segundos' => ['required', 'integer', 'min:0', 'max:86400'],
        ]);

        $semaforo->registrarProgresoVideo(
            (int) $request->user()->id,
            $curso,
            $leccion,
            (int) $datos['segundos'],
        );

        return response()->json(['guardado' => true]);
    }

    public function completarLeccion(Curso $curso, Leccion $leccion, SemaforoAlumnoService $semaforo): RedirectResponse
    {
        abort_unless((int) $leccion->curso_id === (int) $curso->id, 404);
        abort_unless(Matricula::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->exists(), 403);

        $semaforo->marcarLeccionCompletada((int) auth()->id(), $curso, $leccion);

        return to_route('aula.curso', $curso)->with('estado', 'Lección marcada como completada.');
    }

    public function descargarMaterial(LeccionMaterial $material): StreamedResponse
    {
        $material->loadMissing('leccion');
        $matriculado = Matricula::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $material->leccion->curso_id)
            ->where('estado', 'activa')
            ->exists();
        abort_unless(auth()->user()->role === 'alumno' && $matriculado, 403);
        abort_unless(Storage::disk('local')->exists($material->archivo_path), 404);

        return Storage::disk('local')->download($material->archivo_path, $material->nombre_original);
    }

    public function examen(Examen $examen): View
    {
        abort_unless($examen->disponible, 404);
        abort_unless(Matricula::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $examen->curso_id)
            ->where('estado', 'activa')
            ->exists(), 403);

        return view('aula.examen', ['examen' => $examen]);
    }

    public function pagos(): View
    {
        $pagos = Pago::query()->with('curso')->where('user_id', auth()->id())->latest()->get();

        return view('aula.pagos', ['pagos' => $pagos]);
    }
}
