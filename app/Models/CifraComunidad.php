<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CifraComunidad extends Model
{
    protected $table = 'cifras_comunidad';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['publicado' => 'boolean'];
    }
}
