<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reclamacion extends Model
{
    protected $table = 'reclamaciones';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'es_menor' => 'boolean',
            'acepta_privacidad' => 'boolean',
            'fecha_reclamo' => 'date',
            'fecha_compra' => 'date',
            'fecha_consumo' => 'date',
            'fecha_caducidad' => 'date',
            'monto' => 'decimal:2',
        ];
    }
}
