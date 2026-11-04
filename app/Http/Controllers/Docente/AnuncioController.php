<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\AnuncioCurso;
use App\Models\Curso;
use App\Models\User;
use App\Notifications\AnuncioCursoPublicado;
use App\Notifications\NotificacionCentro;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class AnuncioController extends Controller
{
    public function create(Curso $curso): View
    {
        $this->autorizarCurso($curso);

        return view('docente.anuncios.crear', [
            'curso' => $curso,
            'alumnosActivos' => $curso->matriculas()->where('estado', 'activa')->count(),
        ]);
    }

    public function index(Curso $curso): View
    {
        $this->autorizarCurso($curso);

        return view('docente.anuncios.index', [
            'curso' => $curso,
            'anuncios' => $curso->anuncios()->latest('publicado_en')->paginate(20),
            'alumnosActivos' => $curso->matriculas()->where('estado', 'activa')->count(),
        ]);
    }

    public function store(Request $request, Curso $curso): RedirectResponse
    {
        $this->autorizarCurso($curso);

        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:180'],
            'contenido' => ['required', 'string', 'max:8000'],
            'enviar_email' => ['sometimes', 'boolean'],
            'enviar_whatsapp' => ['sometimes', 'boolean'],
        ]);

        $anuncio = AnuncioCurso::query()->create([
            'curso_id' => $curso->id,
            'docente_id' => $request->user()->id,
            'titulo' => $datos['titulo'],
            'contenido' => $datos['contenido'],
            'enviar_email' => $request->boolean('enviar_email'),
            'enviar_whatsapp' => $request->boolean('enviar_whatsapp'),
            'publicado_en' => now(),
        ]);

        $alumnos = $curso->matriculas()
            ->with('usuario')
            ->where('estado', 'activa')
            ->get()
            ->pluck('usuario')
            ->filter(fn ($alumno): bool => $alumno instanceof User && $alumno->role === 'alumno');

        try {
            Notification::send($alumnos, new NotificacionCentro(
                'Nuevo anuncio: '.$anuncio->titulo,
                Str::limit(strip_tags($anuncio->contenido), 180),
                route('aula.curso', $curso, false),
                'anuncio',
            ));
        } catch (Throwable $exception) {
            report($exception);
            Log::warning('No se pudo guardar la notificación del anuncio.', [
                'anuncio_id' => $anuncio->id,
                'curso_id' => $curso->id,
            ]);
        }

        $mensaje = 'Anuncio publicado para los alumnos del curso.';
        if ($anuncio->enviar_email) {
            try {
                Notification::send($alumnos, new AnuncioCursoPublicado($anuncio));
            } catch (Throwable $exception) {
                report($exception);
                Log::warning('No se completó el envío por correo de un anuncio del curso.', [
                    'anuncio_id' => $anuncio->id,
                    'curso_id' => $curso->id,
                ]);
                $mensaje = 'El anuncio quedó publicado, pero no se completó el envío de correos. Revisa la configuración de correo.';
            }
        }

        return redirect()->route('docente.anuncios.index', $curso)
            ->with('estado', $mensaje);
    }

    private function autorizarCurso(Curso $curso): void
    {
        abort_unless((int) $curso->docente_id === (int) auth()->id(), 403);
    }
}
