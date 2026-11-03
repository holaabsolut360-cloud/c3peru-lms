<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'instructores';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['publicado' => 'boolean'];
    }
}
