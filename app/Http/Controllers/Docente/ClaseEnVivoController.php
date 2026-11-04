<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\User;
use App\Notifications\NotificacionCentro;
use App\Services\MicrosoftTeamsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ClaseEnVivoController extends Controller
{
    public function __construct(private readonly MicrosoftTeamsService $teamsService) {}

    public function crear(Request $request, Curso $curso): View
    {
        $this->autorizarCurso($request, $curso);

        return view('docente.clases.crear', [
            'curso' => $curso->load('categoria'),
            'lecciones' => $curso->lecciones()->orderBy('orden')->get(),
        ]);
    }

    public function guardar(Request $request, Curso $curso): RedirectResponse
    {
        $this->autorizarCurso($request, $curso);

        $datos = $request->validate([
            'leccion_id' => [
                'nullable',
                'integer',
                Rule::exists('lecciones', 'id')->where('curso_id', $curso->id),
            ],
            'titulo' => ['required', 'string', 'max:180'],
            'descripcion' => ['nullable', 'string', 'max:4000'],
            'fecha_inicio' => ['required', 'date', 'after:now'],
            'fecha_fin' => ['required', 'date', 'after:fecha_inicio'],
        ], [
            'fecha_inicio.after' => 'La fecha de inicio debe ser futura.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior al inicio.',
        ]);

        $clase = $curso->clasesEnVivo()->create([
            ...$datos,
            'docente_id' => $request->user()->getAuthIdentifier(),
            'estado' => 'programada',
        ]);
        $reunion = $this->teamsService->crearReunion($clase);
        $clase->update([
            'teams_meeting_id' => $reunion['meeting_id'],
            'teams_join_url' => $reunion['join_url'],
        ]);

        $alumnos = $curso->matriculas()
            ->with('usuario')
            ->where('estado', 'activa')
            ->get()
            ->pluck('usuario')
            ->filter(fn ($alumno): bool => $alumno instanceof User && $alumno->role === 'alumno');

        try {
            Notification::send($alumnos, new NotificacionCentro(
                'Nueva clase en vivo',
                $clase->titulo.' · '.$clase->fecha_inicio->timezone(config('app.timezone'))->format('d/m/Y H:i'),
                route('aula.curso', $curso, false),
                'clase',
            ));
        } catch (Throwable $exception) {
            report($exception);
            Log::warning('No se pudo guardar la notificación de la nueva clase.', [
                'clase_id' => $clase->id,
                'curso_id' => $curso->id,
            ]);
        }

        return redirect()->route('docente.inicio')->with('estado', 'Clase programada. Se generó un enlace simulado para revisar la interfaz; no es una reunión real.');
    }

    private function autorizarCurso(Request $request, Curso $curso): void
    {
        abort_unless((int) $curso->docente_id === (int) $request->user()->getAuthIdentifier(), 403);
    }
}
