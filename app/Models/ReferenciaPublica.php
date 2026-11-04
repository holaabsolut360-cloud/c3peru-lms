<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferenciaPublica extends Model
{
    protected $table = 'referencias_publicas';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'por_confirmar' => 'boolean',
            'publicado' => 'boolean',
        ];
    }
}
