<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use App\Notifications\RecordatorioCurso;
use App\Services\SemaforoAlumnoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Throwable;

class AlumnoController extends Controller
{
    public function index(Request $request, Curso $curso, SemaforoAlumnoService $semaforo): View
    {
        $this->autorizarCurso($curso);

        $matriculas = Matricula::query()
            ->with('usuario:id,name,email,telefono')
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->whereHas('usuario', fn ($consulta) => $consulta->where('role', 'alumno'))
            ->latest()
            ->get();
        $estados = $semaforo->calcularEstados($matriculas);
        $alumnosConEstado = $matriculas->map(function (Matricula $matricula) use ($estados, $curso) {
            $estado = $estados[$matricula->id];
            $telefono = preg_replace('/\D+/', '', (string) $matricula->usuario->telefono);
            if (strlen($telefono) === 9) {
                $telefono = '51'.$telefono;
            }
            $mensaje = 'Hola '.$matricula->usuario->name.', te escribo por el curso '.$curso->titulo.'. Tu avance registrado es de '.number_format($estado['progreso'], 0).'%. ¿Necesitas apoyo?';

            return [
                'alumno' => $matricula->usuario,
                'semaforo' => $estado,
                'whatsapp_url' => $telefono !== ''
                    ? 'https://wa.me/'.$telefono.'?text='.rawurlencode($mensaje)
                    : null,
            ];
        });

        $conteo = [
            'verde' => $alumnosConEstado->where('semaforo.estado', 'verde')->count(),
            'amarillo' => $alumnosConEstado->where('semaforo.estado', 'amarillo')->count(),
            'rojo' => $alumnosConEstado->where('semaforo.estado', 'rojo')->count(),
        ];
        $filtro = $request->query('filtro');
        if (! in_array($filtro, ['verde', 'amarillo', 'rojo'], true)) {
            $filtro = null;
        }

        $filtrados = $filtro
            ? $alumnosConEstado->filter(fn (array $alumno): bool => $alumno['semaforo']['estado'] === $filtro)->values()
            : $alumnosConEstado->values();
        $porPagina = 30;
        $pagina = LengthAwarePaginator::resolveCurrentPage();
        $alumnosPaginados = new LengthAwarePaginator(
            $filtrados->forPage($pagina, $porPagina)->values(),
            $filtrados->count(),
            $porPagina,
            $pagina,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('docente.alumnos.index', [
            'curso' => $curso,
            'alumnosConEstado' => $alumnosPaginados,
            'conteo' => $conteo,
            'filtro' => $filtro,
            'totalAlumnos' => $alumnosConEstado->count(),
        ]);
    }

    public function enviarRecordatorio(Curso $curso, User $alumno, SemaforoAlumnoService $semaforo): RedirectResponse
    {
        $this->autorizarCurso($curso);
        abort_unless($alumno->role === 'alumno' && $alumno->email !== null, 404);

        $matricula = Matricula::query()
            ->where('user_id', $alumno->id)
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->firstOrFail();
        $estado = $semaforo->calcularEstado($matricula);

        try {
            Notification::send($alumno, new RecordatorioCurso($curso, $estado['progreso']));
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('estado', 'No se pudo enviar el correo. Revisa la configuración de correo del sitio.');
        }

        return back()->with('estado', 'Se envió un recordatorio por correo a '.$alumno->name.'.');
    }

    private function autorizarCurso(Curso $curso): void
    {
        abort_unless((int) $curso->docente_id === (int) auth()->id(), 403);
    }
}
