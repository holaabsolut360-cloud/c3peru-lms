<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RegistroController extends Controller
{
    public function crear(): View|RedirectResponse
    {
        if ($usuario = Auth::user()) {
            return redirect($usuario->rutaInicio());
        }

        return view('auth.registro');
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'documento_tipo' => ['required', 'in:dni,ce'],
            'documento_numero' => [
                'required', 'string', 'max:20',
                Rule::unique('users', 'documento_numero'),
            ],
            'telefono' => ['nullable', 'string', 'max:30'],
        ], [
            'name.required' => 'Ingresa tu nombre completo.',
            'email.unique' => 'No pudimos crear la cuenta con esos datos. Si ya te registraste, recupera tu contraseña.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'documento_numero.unique' => 'No pudimos crear la cuenta con esos datos. Si ya te registraste, recupera tu contraseña.',
        ]);

        $numero = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $datos['documento_numero']) ?? '');
        if ($datos['documento_tipo'] === 'dni' && ! preg_match('/^\d{8}$/', $numero)) {
            return back()->withInput()->withErrors(['documento_numero' => 'El DNI debe tener 8 dígitos.']);
        }
        if ($datos['documento_tipo'] === 'ce' && ! preg_match('/^[A-Z0-9]{6,12}$/', $numero)) {
            return back()->withInput()->withErrors(['documento_numero' => 'El carné de extranjería no tiene un formato válido.']);
        }

        $usuario = User::query()->create([
            'name' => $datos['name'],
            'email' => mb_strtolower($datos['email']),
            'password' => $datos['password'],
            'role' => 'alumno',
            'documento_tipo' => $datos['documento_tipo'],
            'documento_numero' => $numero,
            'telefono' => $datos['telefono'] ?? null,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('aula.inicio');
    }
}
