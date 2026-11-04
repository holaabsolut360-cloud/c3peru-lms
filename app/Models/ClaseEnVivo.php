<?php

namespace App\Models;

use Database\Factories\ClaseEnVivoFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaseEnVivo extends Model
{
    /** @use HasFactory<ClaseEnVivoFactory> */
    use HasFactory;

    protected $table = 'clases_en_vivo';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
        ];
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function leccion(): BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function estadoActual(): string
    {
        if ($this->estado === 'cancelada') {
            return 'cancelada';
        }

        if ($this->fecha_inicio->isFuture()) {
            return 'programada';
        }

        return $this->fecha_fin->isPast() ? 'finalizada' : 'en_vivo';
    }

    public function esEnlaceSimulado(): bool
    {
        return is_string($this->teams_meeting_id)
            && str_starts_with($this->teams_meeting_id, 'mock-meeting-');
    }

    public function scopeProximas(Builder $query): Builder
    {
        return $query
            ->where('estado', '!=', 'cancelada')
            ->where('fecha_fin', '>=', now())
            ->orderBy('fecha_inicio');
    }
}
