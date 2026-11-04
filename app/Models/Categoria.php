<?php

namespace App\Models;

use Database\Factories\CategoriaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    /** @use HasFactory<CategoriaFactory> */
    use HasFactory;

    protected $table = 'categorias';

    protected $guarded = ['id'];

    public function cursos(): HasMany
    {
        return $this->hasMany(Curso::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
