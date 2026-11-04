<?php

namespace App\Support;

use Illuminate\Support\Facades\RateLimiter;

class LoginIntentos
{
    public const MAXIMO = 3;

    public const MENSAJE_CREDENCIALES = 'Correo o contraseña incorrectos.';

    public const MENSAJE_LIMITE = 'Superaste los 3 intentos permitidos. Recupera o cambia tu contraseña para volver a ingresar.';

    public static function clave(string $email): string
    {
        return 'login-intentos:'.hash('sha256', mb_strtolower(trim($email)));
    }

    public static function excedido(string $email): bool
    {
        return RateLimiter::tooManyAttempts(self::clave($email), self::MAXIMO);
    }

    public static function registrarFallo(string $email): void
    {
        RateLimiter::hit(self::clave($email), 30 * 60);
    }

    public static function limpiar(string $email): void
    {
        RateLimiter::clear(self::clave($email));
    }
}
