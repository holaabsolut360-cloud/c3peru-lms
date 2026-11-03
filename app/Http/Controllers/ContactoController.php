<?php

namespace App\Http\Controllers;

use App\Models\MensajeContacto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactoController extends Controller
{
    public function crear(): View
    {
        return view('contacto');
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:120'],
            'correo' => ['required', 'email', 'max:160'],
            'telefono' => ['nullable', 'string', 'max:40'],
            'especialidad' => ['nullable', 'string', 'max:80'],
            'modalidad' => ['nullable', 'string', 'max:80'],
            'mensaje' => ['required', 'string', 'max:4000'],
        ]);

        MensajeContacto::query()->create($datos);

        return back()->with('estado', 'Recibimos tu mensaje. Te responderemos al correo que indicaste.');
    }
}
