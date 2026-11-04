<?php

use App\Http\Middleware\Rol;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'rol' => Rol::class,
        ]);
        $middleware->redirectGuestsTo('/ingresar');
        $middleware->redirectUsersTo(fn (Request $request) => $request->user()?->rutaInicio() ?? '/');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        $exceptions->render(function (ThrottleRequestsException $excepcion, Request $request) {
            if ($request->is('verificar-certificado')) {
                return back()->withErrors([
                    'consulta' => 'Demasiados intentos de verificación. Espera unos minutos y vuelve a intentar.',
                ])->setStatusCode(429);
            }

            return null;
        });
    })->create();
