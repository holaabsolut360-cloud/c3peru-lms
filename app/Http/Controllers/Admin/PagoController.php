<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Services\PagoAprobador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use RuntimeException;

class PagoController extends Controller
{
    public function index(): View
    {
        return view('admin.pagos', [
            'pagos' => Pago::query()->with(['usuario', 'curso', 'aprobador'])->latest()->get(),
        ]);
    }

    public function aprobar(Pago $pago, PagoAprobador $aprobador): RedirectResponse
    {
        Gate::authorize('aprobar-pagos');

        try {
            $aprobador->aprobar($pago, request()->user());
        } catch (RuntimeException $excepcion) {
            return back()->withErrors(['pago' => $excepcion->getMessage()]);
        }

        return back()->with('estado', 'Pago aprobado y matrícula activada.');
    }

    public function rechazar(Request $request, Pago $pago, PagoAprobador $aprobador): RedirectResponse
    {
        Gate::authorize('aprobar-pagos');
        $datos = $request->validate([
            'motivo' => ['required', 'string', 'max:500'],
        ], [
            'motivo.required' => 'Indica el motivo del rechazo.',
        ]);

        try {
            $aprobador->rechazar($pago, $request->user(), $datos['motivo']);
        } catch (RuntimeException $excepcion) {
            return back()->withErrors(['pago' => $excepcion->getMessage()]);
        }

        return back()->with('estado', 'Pago rechazado. No se creó matrícula.');
    }
}
