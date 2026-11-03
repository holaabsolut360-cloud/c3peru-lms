<?php

namespace App\Support;

use App\Models\CifraComunidad;
use App\Models\Compromiso;
use App\Models\Instructor;
use App\Models\ReferenciaPublica;

class Comunidad
{
    /**
     * @return array<string, mixed>
     */
    public static function datos(): array
    {
        return [
            'cifras' => CifraComunidad::query()->where('publicado', true)->orderBy('orden')->get(),
            'compromisos' => Compromiso::query()->where('publicado', true)->orderBy('orden')->get(),
            'instructores' => Instructor::query()->where('publicado', true)->orderBy('orden')->get(),
            'referencias' => ReferenciaPublica::query()->where('publicado', true)->orderBy('orden')->get()->groupBy('tipo'),
        ];
    }
}
