<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pregunta extends Model
{
    /** @use HasFactory<\Database\Factories\PreguntaFactory> */
    use HasFactory;

    protected $table = 'preguntas';

    protected $guarded = ['id'];

    protected $hidden = ['respuesta_correcta'];

    protected function casts(): array
    {
        return [
            'opciones' => 'array',
        ];
    }

    public function examen(): BelongsTo
    {
        return $this->belongsTo(Examen::class);
    }

    public function leccion(): BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(ExamenRespuesta::class);
    }
}
