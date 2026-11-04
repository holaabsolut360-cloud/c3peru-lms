<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\RubricaCalificacion;
use App\Models\Tarea;
use App\Models\TareaEntrega;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TareaController extends Controller
{
    public function index(Curso $curso): View
    {
        $this->autorizarCurso($curso);

        return view('docente.tareas.index', [
            'curso' => $curso,
            'tareas' => $curso->tareas()
                ->withCount([
                    'rubricas',
                    'entregas',
                    'entregas as entregas_calificadas_count' => fn ($consulta) => $consulta->whereNotNull('calificada_en'),
                ])
                ->latest()
                ->paginate(20),
        ]);
    }

    public function store(Request $request, Curso $curso): RedirectResponse
    {
        $this->autorizarCurso($curso);

        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:180'],
            'instrucciones' => ['required', 'string', 'max:12000'],
            'fecha_entrega' => ['nullable', 'date', 'after_or_equal:today'],
            'criterios' => ['required', 'array', 'min:1', 'max:10'],
            'criterios.*.criterio' => ['required', 'string', 'max:180'],
            'criterios.*.puntaje_maximo' => ['required', 'integer', 'min:1', 'max:100'],
            'criterios.*.descripcion_nivel_alto' => ['nullable', 'string', 'max:2000'],
            'criterios.*.descripcion_nivel_medio' => ['nullable', 'string', 'max:2000'],
            'criterios.*.descripcion_nivel_bajo' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($datos, $curso, $request): void {
            $tarea = Tarea::query()->create([
                'curso_id' => $curso->id,
                'docente_id' => $request->user()->id,
                'titulo' => $datos['titulo'],
                'instrucciones' => $datos['instrucciones'],
                'fecha_entrega' => $datos['fecha_entrega'] ?? null,
                'publicada' => true,
            ]);

            foreach ($datos['criterios'] as $orden => $criterio) {
                $tarea->rubricas()->create([
                    'criterio' => $criterio['criterio'],
                    'puntaje_maximo' => $criterio['puntaje_maximo'],
                    'descripcion_nivel_alto' => $criterio['descripcion_nivel_alto'] ?? null,
                    'descripcion_nivel_medio' => $criterio['descripcion_nivel_medio'] ?? null,
                    'descripcion_nivel_bajo' => $criterio['descripcion_nivel_bajo'] ?? null,
                    'orden' => $orden,
                ]);
            }
        });

        return redirect()->route('docente.tareas.index', $curso)->with('estado', 'Tarea y rúbrica publicadas.');
    }

    public function entregas(Curso $curso, Tarea $tarea): View
    {
        $this->autorizarCurso($curso);
        abort_unless((int) $tarea->curso_id === (int) $curso->id, 404);

        return view('docente.tareas.entregas', [
            'curso' => $curso,
            'tarea' => $tarea->load('rubricas'),
            'entregas' => $tarea->entregas()
                ->with(['alumno:id,name,email', 'calificaciones.rubrica'])
                ->latest('entregado_en')
                ->paginate(30),
        ]);
    }

    public function calificar(TareaEntrega $entrega): View
    {
        $this->autorizarCurso($entrega->tarea()->with('curso')->firstOrFail()->curso);
        $entrega->load(['alumno:id,name,email', 'tarea.curso', 'tarea.rubricas', 'calificaciones']);

        return view('docente.tareas.calificar', [
            'entrega' => $entrega,
            'rubricas' => $entrega->tarea->rubricas,
            'calificaciones' => $entrega->calificaciones->keyBy('rubrica_id'),
            'totalRubrica' => $entrega->tarea->rubricas->sum('puntaje_maximo'),
        ]);
    }

    public function guardarCalificacion(Request $request, TareaEntrega $entrega): RedirectResponse
    {
        $entrega->loadMissing('tarea.curso');
        $this->autorizarCurso($entrega->tarea->curso);
        $rubricas = $entrega->tarea->rubricas()->get();
        abort_if($rubricas->isEmpty(), 422, 'La tarea no tiene criterios de evaluación.');

        $idsRubricas = $rubricas->pluck('id')->map(fn ($id): string => (string) $id)->all();
        $datos = $request->validate([
            'rubricas' => ['required', 'array:'.implode(',', $idsRubricas)],
            'comentario_docente' => ['nullable', 'string', 'max:4000'],
        ]);
        $rules = [];
        foreach ($rubricas as $rubrica) {
            $rules['rubricas.'.$rubrica->id.'.puntaje'] = ['required', 'integer', 'min:0', 'max:'.$rubrica->puntaje_maximo];
            $rules['rubricas.'.$rubrica->id.'.comentario'] = ['nullable', 'string', 'max:2000'];
        }
        $criteriosCalificados = $request->validate($rules)['rubricas'];
        $puntajeTotal = collect($criteriosCalificados)->sum(fn (array $calificacion): int => (int) $calificacion['puntaje']);

        DB::transaction(function () use ($entrega, $rubricas, $criteriosCalificados, $puntajeTotal, $request, $datos): void {
            foreach ($rubricas as $rubrica) {
                $calificacion = $criteriosCalificados[$rubrica->id];
                RubricaCalificacion::query()->updateOrCreate(
                    ['rubrica_id' => $rubrica->id, 'tarea_entrega_id' => $entrega->id],
                    [
                        'docente_id' => $request->user()->id,
                        'puntaje_obtenido' => (int) $calificacion['puntaje'],
                        'comentario' => $calificacion['comentario'] ?? null,
                    ],
                );
            }

            $entrega->update([
                'puntaje_total' => $puntajeTotal,
                'comentario_docente' => $datos['comentario_docente'] ?? null,
                'calificada_en' => now(),
            ]);
        });

        return redirect()->route('docente.tareas.entregas', [$entrega->tarea->curso, $entrega->tarea])
            ->with('estado', 'Calificación guardada y visible para el alumno.');
    }

    private function autorizarCurso(Curso $curso): void
    {
        abort_unless((int) $curso->docente_id === (int) auth()->id(), 403);
    }
}
