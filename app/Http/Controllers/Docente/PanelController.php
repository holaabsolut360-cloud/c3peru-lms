<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Matricula;
use App\Services\SemaforoAlumnoService;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function __invoke(SemaforoAlumnoService $semaforo): View
    {
        $docenteId = (int) auth()->id();
        $cursos = Curso::query()
            ->withCount(['lecciones', 'clasesEnVivo'])
            ->with(['categoria'])
            ->where('docente_id', $docenteId)
            ->orderBy('titulo')
            ->get();

        $matriculas = Matricula::query()
            ->with(['usuario:id,name', 'curso:id,titulo'])
            ->where('estado', 'activa')
            ->whereHas('usuario', fn ($consulta) => $consulta->where('role', 'alumno'))
            ->whereHas('curso', fn ($consulta) => $consulta->where('docente_id', $docenteId))
            ->latest()
            ->orderByDesc('id')
            ->paginate(50);

        return view('docente.inicio', [
            'cursos' => $cursos,
            'matriculas' => $matriculas,
            'estadosAlumnos' => $semaforo->calcularEstados($matriculas->getCollection()),
        ]);
    }
}
