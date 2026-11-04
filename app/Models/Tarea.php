<?php

namespace App\Models;

use Database\Factories\TareaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tarea extends Model
{
    /** @use HasFactory<TareaFactory> */
    use HasFactory;

    protected $table = 'tareas';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'fecha_entrega' => 'datetime',
            'publicada' => 'boolean',
        ];
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function entregas(): HasMany
    {
        return $this->hasMany(TareaEntrega::class);
    }

    public function rubricas(): HasMany
    {
        return $this->hasMany(Rubrica::class)->orderBy('orden');
    }
}
