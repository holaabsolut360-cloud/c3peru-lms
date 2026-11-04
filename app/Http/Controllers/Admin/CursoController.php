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
            'beneficio' => ['nullable', 'string', 'max:500'],
            'aprendizajes' => ['nullable', 'string', 'max:8000'],
            'temario' => ['nullable', 'string', 'max:12000'],
            'dirigido_a' => ['nullable', 'string', 'max:2000'],
            'requisitos' => ['nullable', 'string', 'max:2000'],
            'horario' => ['nullable', 'string', 'max:160'],
            'fecha_inicio' => ['nullable', 'string', 'max:160'],
            'instructor_nombre' => ['nullable', 'string', 'max:160'],
            'instructor_cargo' => ['nullable', 'string', 'max:160'],
            'instructor_enlace' => ['nullable', 'url', 'max:255'],
            'preguntas' => ['nullable', 'string', 'max:8000'],
            'testimonios' => ['nullable', 'string', 'max:8000'],
            'incluye' => ['nullable', 'string', 'max:4000'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'portada' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp'],
            'instructor_foto' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp', 'mimetypes:image/jpeg,image/png,image/webp'],
            'temario_pdf' => ['nullable', 'file', 'max:8192', 'mimes:pdf', 'mimetypes:application/pdf'],
        ], [
            'titulo.required' => 'Escribe el nombre del curso.',
            'categoria_id.required' => 'Elige una categoría.',
            'portada.mimes' => 'La portada debe ser JPG, PNG o WEBP.',
        ]);

        $datos['precio'] = $request->filled('precio') ? $datos['precio'] : null;
        $datos['orden'] = $datos['orden'] ?? ($curso->orden ?? (Curso::query()->max('orden') + 1));
        $datos['aprendizajes'] = $this->lineas($datos['aprendizajes'] ?? null);
        $datos['incluye'] = $this->lineas($datos['incluye'] ?? null);
        $datos['temario'] = $this->pares($datos['temario'] ?? null, ['titulo', 'descripcion']);
        $datos['preguntas'] = $this->pares($datos['preguntas'] ?? null, ['pregunta', 'respuesta']);
        $datos['testimonios'] = $this->pares($datos['testimonios'] ?? null, ['nombre', 'texto']);
        unset($datos['portada'], $datos['instructor_foto'], $datos['temario_pdf']);
        if ($foto = $this->archivo($request, 'instructor_foto', 'instructores')) {
            $datos['instructor_foto'] = $foto;
        }
        if ($pdf = $this->archivo($request, 'temario_pdf', 'temarios')) {
            $datos['temario_archivo'] = $pdf;
        }

        return $datos;
    }

    private function portada(Request $request): ?string
    {
        return $this->archivo($request, 'portada', 'cursos');
    }

    private function archivo(Request $request, string $campo, string $carpeta): ?string
    {
        if (! $request->hasFile($campo)) {
            return null;
        }

        return '/storage/'.$request->file($campo)->store($carpeta, 'public');
    }

    /**
     * @return array<int, string>
     */
    private function lineas(?string $texto): array
    {
        $partes = preg_split('/\r\n|\r|\n/', (string) $texto) ?: [];

        return array_values(array_filter(array_map('trim', $partes)));
    }

    /**
     * @param  array<int, string>  $claves
     * @return array<int, array<string, string>>
     */
    private function pares(?string $texto, array $claves): array
    {
        $salida = [];
        foreach ($this->lineas($texto) as $linea) {
            $partes = array_map('trim', explode('||', $linea, count($claves)));
            if (($partes[0] ?? '') === '') {
                continue;
            }
            $fila = [];
            foreach ($claves as $indice => $clave) {
                $fila[$clave] = $partes[$indice] ?? '';
            }
            $salida[] = $fila;
        }

        return $salida;
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
