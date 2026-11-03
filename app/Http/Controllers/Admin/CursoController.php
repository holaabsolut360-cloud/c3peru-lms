<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CursoController extends Controller
{
    public function index(): View
    {
        return view('admin.cursos.index', [
            'cursos' => Curso::query()->with('categoria')->orderBy('orden')->get(),
        ]);
    }

    public function crear(): View
    {
        return view('admin.cursos.formulario', [
            'curso' => new Curso(['publicado' => true, 'incluye_igv' => true, 'moneda' => 'PEN']),
            'categorias' => Categoria::query()->orderBy('orden')->get(),
        ]);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['slug'] = $this->slug($datos['titulo'], $datos['nivel'] ?? null);
        $datos['portada'] = $this->portada($request) ?? $this->portadaDeCategoria((int) $datos['categoria_id']);
        $datos['publicado'] = $request->boolean('publicado');
        $datos['incluye_igv'] = $request->boolean('incluye_igv');
        $datos['destacado'] = $request->boolean('destacado');
        $datos['moneda'] = 'PEN';

        Curso::query()->create($datos);

        return redirect()->route('admin.cursos')->with('estado', 'Curso creado.');
    }

    public function editar(Curso $curso): View
    {
        return view('admin.cursos.formulario', [
            'curso' => $curso,
            'categorias' => Categoria::query()->orderBy('orden')->get(),
        ]);
    }

    public function actualizar(Request $request, Curso $curso): RedirectResponse
    {
        $datos = $this->validar($request, $curso);
        $datos['slug'] = $this->slug($datos['titulo'], $datos['nivel'] ?? null, $curso->id);
        $datos['publicado'] = $request->boolean('publicado');
        $datos['incluye_igv'] = $request->boolean('incluye_igv');
        $datos['destacado'] = $request->boolean('destacado');
        if ($portada = $this->portada($request)) {
            $datos['portada'] = $portada;
        }

        $curso->update($datos);

        return redirect()->route('admin.cursos')->with('estado', 'Curso actualizado.');
    }

    public function eliminar(Curso $curso): RedirectResponse
    {
        if ($curso->matriculas()->exists() || $curso->pagos()->exists()) {
            return back()->withErrors(['curso' => 'Este curso tiene matrículas o pagos. No se elimina.']);
        }

        $curso->lecciones()->delete();
        $curso->delete();

        return redirect()->route('admin.cursos')->with('estado', 'Curso eliminado.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request, ?Curso $curso = null): array
    {
        $datos = $request->validate([
            'categoria_id' => ['required', 'exists:categorias,id'],
            'titulo' => ['required', 'string', 'max:180'],
            'nivel' => ['nullable', 'string', 'max:160'],
            'duracion' => ['nullable', 'string', 'max:160'],
            'modalidad' => ['nullable', 'string', 'max:160'],
            'fechas' => ['nullable', 'string', 'max:160'],
            'certificacion' => ['nullable', 'string', 'max:180'],
            'precio' => ['nullable', 'numeric', 'min:0'],
            'descripcion' => ['nullable', 'string', 'max:5000'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'portada' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp'],
        ], [
            'titulo.required' => 'Escribe el nombre del curso.',
            'categoria_id.required' => 'Elige una categoría.',
            'portada.mimes' => 'La portada debe ser JPG, PNG o WEBP.',
        ]);

        $datos['precio'] = $request->filled('precio') ? $datos['precio'] : null;
        $datos['orden'] = $datos['orden'] ?? ($curso->orden ?? (Curso::query()->max('orden') + 1));
        unset($datos['portada']);

        return $datos;
    }

    private function portada(Request $request): ?string
    {
        if (! $request->hasFile('portada')) {
            return null;
        }

        $ruta = $request->file('portada')->store('cursos', 'public');

        return '/storage/'.$ruta;
    }

    private function portadaDeCategoria(int $categoriaId): string
    {
        $slug = Categoria::query()->whereKey($categoriaId)->value('slug');

        return match ($slug) {
            'izaje' => '/marca/izajes.jpg',
            'end-ndt' => '/marca/end.jpg',
            'oil-gas' => '/marca/oilgas.jpg',
            'mantenimiento' => '/marca/mantenimiento.jpg',
            default => '/marca/hero.jpg',
        };
    }

    private function slug(string $titulo, ?string $nivel, ?int $ignorar = null): string
    {
        $base = Str::slug(trim($titulo.' '.($nivel ?? ''))) ?: 'curso';
        $slug = $base;
        $n = 2;
        while (Curso::query()->where('slug', $slug)->when($ignorar, fn ($q) => $q->where('id', '!=', $ignorar))->exists()) {
            $slug = $base.'-'.$n;
            $n++;
        }

        return $slug;
    }
}
