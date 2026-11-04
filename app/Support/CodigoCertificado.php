<?php

namespace App\Support;

use App\Models\Certificado;
use App\Models\ConfiguracionApariencia;

class CodigoCertificado
{
    public static function nuevo(): string
    {
        $prefijo = preg_replace('/[^A-Za-z0-9]/', '', (string) ConfiguracionApariencia::actual()->prefijo_certificado) ?: 'C3';
        $alfabeto = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $largo = strlen($alfabeto);

        do {
            $sufijo = '';
            for ($i = 0; $i < 6; $i++) {
                $sufijo .= $alfabeto[random_int(0, $largo - 1)];
            }
            $codigo = $prefijo.'-'.now()->year.'-'.$sufijo;
        } while (Certificado::query()->where('codigo', $codigo)->exists());

        return $codigo;
    }

    public static function pareceCodigo(string $consulta): bool
    {
        $prefijo = preg_replace('/[^A-Za-z0-9]/', '', (string) ConfiguracionApariencia::actual()->prefijo_certificado) ?: 'C3';

        return (bool) preg_match('/^'.preg_quote($prefijo, '/').'-\d{4}-[A-Z0-9]{6}$/i', trim($consulta));
    }
}
