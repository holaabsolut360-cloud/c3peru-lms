<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamenRespuesta extends Model
{
    /** @use HasFactory<\Database\Factories\ExamenRespuestaFactory> */
    use HasFactory;

    protected $table = 'examen_respuestas';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'es_correcta' => 'boolean',
        ];
    }

    public function intento(): BelongsTo
    {
        return $this->belongsTo(ExamenIntento::class, 'examen_intento_id');
    }

    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class);
    }
}
