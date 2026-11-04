<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examen extends Model
{
    /** @use HasFactory<\Database\Factories\ExamenFactory> */
    use HasFactory;

    protected $table = 'examenes';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'disponible' => 'boolean',
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'puntaje_aprobatorio' => 'decimal:2',
        ];
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function preguntas(): HasMany
    {
        return $this->hasMany(Pregunta::class)->orderBy('orden');
    }

    public function intentos(): HasMany
    {
        return $this->hasMany(ExamenIntento::class);
    }
}
