<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CifraComunidad;
use App\Models\Compromiso;
use App\Models\Instructor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComunidadAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.comunidad', [
            'cifras' => CifraComunidad::query()->orderBy('orden')->get(),
            'compromisos' => Compromiso::query()->orderBy('orden')->get(),
            'instructores' => Instructor::query()->orderBy('orden')->get(),
        ]);
    }

    public function guardarCifra(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'etiqueta' => ['required', 'string', 'max:120'],
            'valor' => ['required', 'string', 'max:80'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
        $datos['publicado'] = $request->boolean('publicado');
        CifraComunidad::query()->create($datos);

        return back()->with('estado', 'Cifra guardada.');
    }

    public function actualizarCifra(Request $request, CifraComunidad $cifra): RedirectResponse
    {
        $datos = $request->validate([
            'etiqueta' => ['required', 'string', 'max:120'],
            'valor' => ['required', 'string', 'max:80'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
        $datos['publicado'] = $request->boolean('publicado');
        $cifra->update($datos);

        return back()->with('estado', 'Cifra actualizada.');
    }

    public function guardarCompromiso(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:160'],
            'texto' => ['required', 'string', 'max:4000'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
        $datos['propuesto'] = $request->boolean('propuesto');
        $datos['publicado'] = $request->boolean('publicado');
        Compromiso::query()->create($datos);

        return back()->with('estado', 'Compromiso guardado.');
    }

    public function actualizarCompromiso(Request $request, Compromiso $compromiso): RedirectResponse
    {
        $datos = $request->validate([
            'titulo' => ['required', 'string', 'max:160'],
            'texto' => ['required', 'string', 'max:4000'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
        $datos['propuesto'] = $request->boolean('propuesto');
        $datos['publicado'] = $request->boolean('publicado');
        $compromiso->update($datos);

        return back()->with('estado', 'Compromiso actualizado.');
    }

    public function actualizarInstructor(Request $request, Instructor $instructor): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:160'],
            'cargo' => ['required', 'string', 'max:180'],
            'bio' => ['required', 'string', 'max:4000'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'iniciales' => ['required', 'string', 'max:4'],
            'credenciales' => ['nullable', 'string', 'max:1000'],
            'orden' => ['nullable', 'integer', 'min:0'],
            'foto' => ['nullable', 'file', 'max:4096', 'mimes:jpg,jpeg,png,webp'],
        ]);
        $datos['credenciales'] = array_values(array_filter(array_map(
            'trim',
            preg_split('/\r\n|\r|\n/', (string) ($datos['credenciales'] ?? '')) ?: []
        )));
        unset($datos['foto']);
        $datos['publicado'] = $request->boolean('publicado');
        if ($request->hasFile('foto')) {
            $datos['foto'] = '/storage/'.$request->file('foto')->store('instructores', 'public');
        }
        $instructor->update($datos);

        return back()->with('estado', 'Instructor actualizado.');
    }
}
