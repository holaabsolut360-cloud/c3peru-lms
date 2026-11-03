<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferenciaPublica;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferenciaController extends Controller
{
    public function index(): View
    {
        return view('admin.referencias', [
            'referencias' => ReferenciaPublica::query()->orderBy('tipo')->orderBy('orden')->get(),
        ]);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['por_confirmar'] = $request->boolean('por_confirmar');
        $datos['publicado'] = $request->boolean('publicado');
        ReferenciaPublica::query()->create($datos);

        return back()->with('estado', 'Referencia creada.');
    }

    public function actualizar(Request $request, ReferenciaPublica $referencia): RedirectResponse
    {
        $datos = $this->validar($request);
        $datos['por_confirmar'] = $request->boolean('por_confirmar');
        $datos['publicado'] = $request->boolean('publicado');
        $referencia->update($datos);

        return back()->with('estado', 'Referencia actualizada.');
    }

    public function eliminar(ReferenciaPublica $referencia): RedirectResponse
    {
        $referencia->delete();

        return back()->with('estado', 'Referencia eliminada.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validar(Request $request): array
    {
        return $request->validate([
            'tipo' => ['required', 'in:testimonio,aliado,alianza'],
            'nombre' => ['required', 'string', 'max:160'],
            'detalle' => ['nullable', 'string', 'max:180'],
            'texto' => ['nullable', 'string', 'max:2000'],
            'orden' => ['nullable', 'integer', 'min:0'],
        ]);
    }
}
