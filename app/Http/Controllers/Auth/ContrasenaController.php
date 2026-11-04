<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\LoginIntentos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ContrasenaController extends Controller
{
    public function solicitar(): View
    {
        return view('auth.recuperar');
    }

    public function enviar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Ingresa tu correo.',
            'email.email' => 'El correo no tiene un formato válido.',
        ]);

        Password::sendResetLink([
            'email' => mb_strtolower(trim($datos['email'])),
        ]);

        return back()->with('estado', 'Si el correo está registrado, te enviaremos instrucciones para restablecer la contraseña.');
    }

    public function editar(Request $request, string $token): View
    {
        return view('auth.restablecer', [
            'token' => $token,
            'email' => $request->string('email')->toString(),
        ]);
    }

    public function actualizar(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $estado = Password::reset(
            [
                'email' => mb_strtolower(trim($request->string('email')->toString())),
                'password' => $request->input('password'),
                'password_confirmation' => $request->input('password_confirmation'),
                'token' => $request->input('token'),
            ],
            function (User $usuario, string $password): void {
                $usuario->forceFill(['password' => $password])->save();
                LoginIntentos::limpiar($usuario->email);
            }
        );

        if ($estado !== Password::PASSWORD_RESET) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'No pudimos restablecer la contraseña. Solicita un enlace nuevo.',
            ]);
        }

        return redirect()->route('login')->with('estado', 'Tu contraseña fue actualizada. Ya puedes ingresar.');
    }

    public function cambiarFormulario(): View
    {
        return view('auth.cambiar');
    }

    public function cambiar(Request $request): RedirectResponse
    {
        $request->validate([
            'password_actual' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password_actual.current_password' => 'La contraseña actual no coincide.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $request->user()->update(['password' => $request->input('password')]);
        LoginIntentos::limpiar($request->user()->email);

        return back()->with('estado', 'Tu contraseña fue actualizada.');
    }
}
