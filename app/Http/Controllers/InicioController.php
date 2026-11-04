<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Curso;
use App\Support\Comunidad;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function __invoke(): View
    {
        return view('inicio', [
            'especialidades' => Categoria::query()->with(['cursos' => function ($consulta) {
                $consulta->where('publicado', true)->orderBy('orden');
            }])->orderBy('orden')->get(),
            'destacados' => Curso::query()
                ->with('categoria')
                ->where('publicado', true)
                ->where('destacado', true)
                ->orderBy('orden')
                ->get(),
            ...Comunidad::datos(),
        ]);
    }
}
