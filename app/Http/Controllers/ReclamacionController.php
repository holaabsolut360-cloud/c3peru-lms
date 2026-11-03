<?php

namespace App\Http\Controllers;

use App\Models\Reclamacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReclamacionController extends Controller
{
    public function crear(): View
    {
        return view('reclamaciones.crear', [
            'departamentos' => config('peru.departamentos'),
        ]);
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:80'],
            'primer_apellido' => ['required', 'string', 'max:80'],
            'segundo_apellido' => ['nullable', 'string', 'max:80'],
            'tipo_documento' => ['required', 'in:dni,ce,pasaporte,ruc'],
            'numero_documento' => ['required', 'string', 'max:20'],
            'celular' => ['required', 'string', 'max:30'],
            'departamento' => ['required', Rule::in(config('peru.departamentos'))],
            'provincia' => ['required', 'string', 'max:80'],
            'distrito' => ['required', 'string', 'max:80'],
            'direccion' => ['required', 'string', 'max:180'],
            'referencia' => ['nullable', 'string', 'max:180'],
            'correo' => ['required', 'email', 'max:160'],
            'es_menor' => ['required', 'in:0,1'],
            'tutor_nombre' => ['required_if:es_menor,1', 'nullable', 'string', 'max:120'],
            'tutor_correo' => ['required_if:es_menor,1', 'nullable', 'email', 'max:160'],
            'tutor_tipo_documento' => ['required_if:es_menor,1', 'nullable', 'in:dni,ce,pasaporte,ruc'],
            'tutor_numero_documento' => ['required_if:es_menor,1', 'nullable', 'string', 'max:20'],
            'tipo' => ['required', 'in:reclamacion,queja'],
            'tipo_consumo' => ['required', 'in:producto,servicio'],
            'numero_pedido' => ['required', 'string', 'max:40'],
            'fecha_reclamo' => ['required', 'date'],
            'proveedor' => ['nullable', 'string', 'max:160'],
            'monto' => ['nullable', 'numeric', 'min:0'],
            'descripcion_bien' => ['required', 'string', 'max:2000'],
            'fecha_compra' => ['nullable', 'date'],
            'fecha_consumo' => ['nullable', 'date'],
            'fecha_caducidad' => ['nullable', 'date'],
            'detalle' => ['required', 'string', 'max:4000'],
            'pedido_cliente' => ['required', 'string', 'max:2000'],
            'acepta_privacidad' => ['accepted'],
        ]);

        $reclamacion = Reclamacion::query()->create([
            ...$datos,
            'numero_documento' => strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $datos['numero_documento']) ?? ''),
            'es_menor' => $datos['es_menor'] === '1',
            'acepta_privacidad' => true,
            'codigo' => $this->codigo(),
            'estado' => 'recibido',
            'proveedor' => $datos['proveedor'] ?: config('marca.razon_social'),
        ]);

        return redirect()->route('reclamaciones.crear')->with('codigo_reclamo', $reclamacion->codigo);
    }

    private function codigo(): string
    {
        do {
            $codigo = sprintf('C3-LR-%d-%04d', now()->year, random_int(1, 9999));
        } while (Reclamacion::query()->where('codigo', $codigo)->exists());

        return $codigo;
    }
}
