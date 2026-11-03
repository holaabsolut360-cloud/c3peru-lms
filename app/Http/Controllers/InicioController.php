<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\View\View;

class InicioController extends Controller
{
    public function __invoke(): View
    {
        $destacados = Curso::query()
            ->with('categoria')
            ->where('publicado', true)
            ->where('destacado', true)
            ->orderBy('orden')
            ->get();

        return view('inicio', [
            'destacados' => $destacados,
        ]);
    }
}
