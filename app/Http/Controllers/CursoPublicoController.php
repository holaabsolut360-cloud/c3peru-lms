<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CursoPublicoController extends Controller
{
    public function index(Request $request): View
    {
        $categoria = $request->string('categoria')->toString();
        $cursos = Curso::query()
            ->with('categoria')
            ->where('publicado', true)
            ->when($categoria !== '', function ($consulta) use ($categoria) {
                $consulta->whereHas('categoria', fn ($q) => $q->where('slug', $categoria));
            })
            ->orderBy('orden')
            ->paginate(9)
            ->withQueryString();

        return view('cursos.index', [
            'cursos' => $cursos,
            'categorias' => Categoria::query()->orderBy('orden')->get(),
            'categoriaActiva' => $categoria,
        ]);
    }

    public function ver(Curso $curso): View
    {
        abort_unless($curso->publicado, 404);
        $curso->load('categoria', 'docente');
        $relacionados = Curso::query()
            ->with('categoria')
            ->where('publicado', true)
            ->where('categoria_id', $curso->categoria_id)
            ->whereKeyNot($curso->id)
            ->orderBy('orden')
            ->limit(3)
            ->get();

        return view('cursos.ver', [
            'curso' => $curso,
            'relacionados' => $relacionados,
        ]);
    }
}
