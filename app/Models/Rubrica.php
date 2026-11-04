<?php

namespace App\Models;

use Database\Factories\RubricaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rubrica extends Model
{
    /** @use HasFactory<RubricaFactory> */
    use HasFactory;

    protected $table = 'rubricas';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'puntaje_maximo' => 'integer',
            'orden' => 'integer',
        ];
    }

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(RubricaCalificacion::class);
    }
}
