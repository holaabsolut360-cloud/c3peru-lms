<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ExamenController extends Controller
{
    public function index(Curso $curso): View
    {
        return view('admin.examenes.index', [
            'curso' => $curso,
            'examenes' => $curso->examenes()
                ->withCount('preguntas')
                ->withCount(['intentos as intentos_completados_count' => fn ($consulta) => $consulta->whereNotNull('finalizado_en')])
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }

    public function store(Request $request, Curso $curso): RedirectResponse
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:180'],
            'instrucciones' => ['nullable', 'string', 'max:4000'],
            'duracion_minutos' => ['required', 'integer', 'min:1', 'max:240'],
            'puntaje_aprobatorio' => ['required', 'numeric', 'min:0', 'max:20'],
            'intentos_permitidos' => ['required', 'integer', 'min:1', 'max:10'],
            'preguntas' => ['required', 'string', 'max:30000'],
        ]);
        $preguntas = $this->analizarPreguntas($datos['preguntas']);

        DB::transaction(function () use ($curso, $request, $datos, $preguntas): void {
            $examen = $curso->examenes()->create([
                'titulo' => $datos['titulo'],
                'instrucciones' => $datos['instrucciones'] ?? null,
                'duracion_minutos' => $datos['duracion_minutos'],
                'puntaje_aprobatorio' => $datos['puntaje_aprobatorio'],
                'intentos_permitidos' => $datos['intentos_permitidos'],
                'disponible' => $request->boolean('disponible'),
            ]);

            foreach ($preguntas as $orden => $pregunta) {
                $examen->preguntas()->create([...$pregunta, 'orden' => $orden + 1]);
            }
        });

        return redirect()->route('admin.cursos.examenes', $curso)->with('estado', 'Examen creado con sus preguntas.');
    }

    /**
     * @return array<int, array{enunciado: string, opciones: array<string, string>, respuesta_correcta: string, puntaje: int}>
     */
    private function analizarPreguntas(string $texto): array
    {
        $filas = preg_split('/\r\n|\r|\n/', trim($texto)) ?: [];
        $preguntas = [];

        foreach ($filas as $indice => $fila) {
            $campos = array_map('trim', explode('||', $fila));
            if (count($campos) !== 7) {
                throw ValidationException::withMessages([
                    'preguntas' => 'La pregunta '.($indice + 1).' debe tener enunciado, cuatro opciones, letra correcta y puntaje separados por ||.',
                ]);
            }

            [$enunciado, $opcionA, $opcionB, $opcionC, $opcionD, $correcta, $puntaje] = $campos;
            $opciones = ['A' => $opcionA, 'B' => $opcionB, 'C' => $opcionC, 'D' => $opcionD];

            if ($enunciado === '' || in_array('', $opciones, true) || ! in_array(strtoupper($correcta), ['A', 'B', 'C', 'D'], true)) {
                throw ValidationException::withMessages(['preguntas' => 'Completa el enunciado, las cuatro opciones y la respuesta correcta de la pregunta '.($indice + 1).'.']);
            }

            if (! ctype_digit($puntaje) || (int) $puntaje < 1 || (int) $puntaje > 100) {
                throw ValidationException::withMessages(['preguntas' => 'El puntaje de la pregunta '.($indice + 1).' debe ser un entero entre 1 y 100.']);
            }

            $preguntas[] = [
                'enunciado' => $enunciado,
                'opciones' => $opciones,
                'respuesta_correcta' => strtoupper($correcta),
                'puntaje' => (int) $puntaje,
            ];
        }

        if ($preguntas === []) {
            throw ValidationException::withMessages(['preguntas' => 'Agrega al menos una pregunta.']);
        }

        return $preguntas;
    }
}
