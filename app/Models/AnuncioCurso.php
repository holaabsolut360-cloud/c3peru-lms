<?php

namespace App\Models;

use Database\Factories\AnuncioCursoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnuncioCurso extends Model
{
    /** @use HasFactory<AnuncioCursoFactory> */
    use HasFactory;

    protected $table = 'anuncios';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'enviar_email' => 'boolean',
            'enviar_whatsapp' => 'boolean',
            'publicado_en' => 'datetime',
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
}
