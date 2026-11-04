<?php

namespace App\Models;

use Database\Factories\RubricaCalificacionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RubricaCalificacion extends Model
{
    /** @use HasFactory<RubricaCalificacionFactory> */
    use HasFactory;

    protected $table = 'rubrica_calificaciones';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['puntaje_obtenido' => 'integer'];
    }

    public function rubrica(): BelongsTo
    {
        return $this->belongsTo(Rubrica::class);
    }

    public function entrega(): BelongsTo
    {
        return $this->belongsTo(TareaEntrega::class, 'tarea_entrega_id');
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }
}
