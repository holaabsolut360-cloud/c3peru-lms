<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compromiso extends Model
{
    protected $table = 'compromisos';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'propuesto' => 'boolean',
            'publicado' => 'boolean',
        ];
    }
}
