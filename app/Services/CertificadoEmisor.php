<?php

namespace App\Services;

use App\Models\Certificado;
use App\Models\Matricula;
use App\Support\CodigoCertificado;
use RuntimeException;

class CertificadoEmisor
{
    public function emitir(Matricula $matricula): Certificado
    {
        $matricula->loadMissing('usuario', 'curso', 'certificado');

        if ($matricula->certificado) {
            return $matricula->certificado;
        }

        $usuario = $matricula->usuario;
        if (! $usuario || ! $usuario->documento_tipo || ! $usuario->documento_numero) {
            throw new RuntimeException('El alumno no tiene DNI o CE registrado. No se puede emitir el certificado.');
        }

        return Certificado::query()->create([
            'user_id' => $usuario->id,
            'curso_id' => $matricula->curso_id,
            'matricula_id' => $matricula->id,
            'codigo' => CodigoCertificado::nuevo(),
            'documento_tipo' => $usuario->documento_tipo,
            'documento_numero' => $usuario->documento_numero,
            'nombre' => $usuario->name,
            'curso_titulo' => $matricula->curso->titulo,
            'horas' => $matricula->curso->duracion,
            'emitido_en' => now(),
            'estado' => 'vigente',
        ]);
    }
}
