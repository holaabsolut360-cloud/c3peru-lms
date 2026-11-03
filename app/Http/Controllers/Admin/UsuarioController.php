<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(): View
    {
        return view('admin.usuarios', [
            'usuarios' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function crearDocente(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'documento_tipo' => ['nullable', 'in:dni,ce'],
            'documento_numero' => ['nullable', 'string', 'max:20'],
        ]);

        User::query()->create([
            'name' => $datos['name'],
            'email' => mb_strtolower($datos['email']),
            'password' => $datos['password'],
            'role' => 'docente',
            'documento_tipo' => $datos['documento_tipo'] ?: null,
            'documento_numero' => $datos['documento_numero'] ? strtoupper($datos['documento_numero']) : null,
            'email_verified_at' => now(),
        ]);

        return back()->with('estado', 'Docente creado. Puede ingresar y recuperar su contraseña como cualquier alumno.');
    }

    public function actualizarDocumento(Request $request, User $usuario): RedirectResponse
    {
        $datos = $request->validate([
            'documento_tipo' => ['required', 'in:dni,ce'],
            'documento_numero' => ['required', 'string', 'max:20'],
        ]);
        $numero = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $datos['documento_numero']) ?? '');
        $usuario->update([
            'documento_tipo' => $datos['documento_tipo'],
            'documento_numero' => $numero,
        ]);

        return back()->with('estado', 'Documento actualizado. Los certificados nuevos guardarán este dato.');
    }
}
