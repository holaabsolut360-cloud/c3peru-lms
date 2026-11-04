<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamenIntento extends Model
{
    /** @use HasFactory<\Database\Factories\ExamenIntentoFactory> */
    use HasFactory;

    protected $table = 'examen_intentos';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'iniciado_en' => 'datetime',
            'expira_en' => 'datetime',
            'finalizado_en' => 'datetime',
            'nota_final' => 'decimal:2',
            'aprobado' => 'boolean',
        ];
    }

    public function examen(): BelongsTo
    {
        return $this->belongsTo(Examen::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function respuestas(): HasMany
    {
        return $this->hasMany(ExamenRespuesta::class);
    }
}
