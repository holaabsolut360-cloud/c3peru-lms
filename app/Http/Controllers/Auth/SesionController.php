<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\LoginIntentos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SesionController extends Controller
{
    public function crear(): View|RedirectResponse
    {
        if ($usuario = Auth::user()) {
            return redirect($usuario->rutaInicio());
        }

        return view('auth.ingresar');
    }

    public function guardar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Ingresa tu correo.',
            'email.email' => 'El correo no tiene un formato válido.',
            'password.required' => 'Ingresa tu contraseña.',
        ]);

        $email = mb_strtolower(trim($datos['email']));

        if (LoginIntentos::excedido($email)) {
            return $this->limite($request);
        }

        if (! Auth::attempt(['email' => $email, 'password' => $datos['password']], $request->boolean('remember'))) {
            LoginIntentos::registrarFallo($email);

            if (LoginIntentos::excedido($email)) {
                return $this->limite($request);
            }

            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => LoginIntentos::MENSAJE_CREDENCIALES]);
        }

        LoginIntentos::limpiar($email);
        $request->session()->regenerate();

        return redirect()->intended($request->user()->rutaInicio());
    }

    public function destruir(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }

    private function limite(Request $request): RedirectResponse
    {
        return back()
            ->withInput($request->only('email'))
            ->with('limite_login', true)
            ->withErrors(['email' => LoginIntentos::MENSAJE_LIMITE]);
    }
}
