<?php

namespace App\Models;

use Database\Factories\TareaEntregaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TareaEntrega extends Model
{
    /** @use HasFactory<TareaEntregaFactory> */
    use HasFactory;

    protected $table = 'tarea_entregas';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'entregado_en' => 'datetime',
            'calificada_en' => 'datetime',
            'puntaje_total' => 'integer',
        ];
    }

    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(RubricaCalificacion::class, 'tarea_entrega_id');
    }
}
