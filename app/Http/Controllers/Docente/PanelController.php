<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function __invoke(): View
    {
        $cursos = Curso::query()
            ->withCount('lecciones')
            ->with(['categoria'])
            ->where('docente_id', auth()->id())
            ->orderBy('titulo')
            ->get();

        return view('docente.inicio', ['cursos' => $cursos]);
    }
}
