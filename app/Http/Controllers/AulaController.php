<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pago;
use Illuminate\View\View;

class AulaController extends Controller
{
    public function inicio(): View
    {
        $matriculas = Matricula::query()
            ->with('curso.categoria')
            ->where('user_id', auth()->id())
            ->where('estado', 'activa')
            ->latest()
            ->get();

        return view('aula.inicio', ['matriculas' => $matriculas]);
    }

    public function curso(Curso $curso): View
    {
        $matricula = Matricula::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->first();
        abort_unless($matricula, 403);

        return view('aula.curso', [
            'curso' => $curso,
            'lecciones' => $curso->lecciones()->orderBy('orden')->get(),
        ]);
    }

    public function pagos(): View
    {
        $pagos = Pago::query()->with('curso')->where('user_id', auth()->id())->latest()->get();

        return view('aula.pagos', ['pagos' => $pagos]);
    }
}
