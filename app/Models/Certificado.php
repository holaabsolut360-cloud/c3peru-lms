<?php

namespace App\Models;

use Database\Factories\CertificadoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificado extends Model
{
    /** @use HasFactory<CertificadoFactory> */
    use HasFactory;

    protected $table = 'certificados';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'emitido_en' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function matricula(): BelongsTo
    {
        return $this->belongsTo(Matricula::class);
    }

    public function nombreEnmascarado(): string
    {
        $partes = preg_split('/\s+/u', trim($this->nombre)) ?: [];
        $partes = array_values(array_filter($partes, fn ($parte) => $parte !== ''));
        if ($partes === []) {
            return '';
        }

        $primero = array_shift($partes);
        $resto = array_map(function (string $parte): string {
            $inicial = mb_substr($parte, 0, 1);
            $largo = max(1, mb_strlen($parte) - 1);

            return $inicial.str_repeat('*', $largo);
        }, $partes);

        return trim($primero.' '.implode(' ', $resto));
    }

    public function estadoLegible(): string
    {
        return match ($this->estado) {
            'vigente' => 'Vigente',
            'revocado' => 'Revocado',
            default => 'No vigente',
        };
    }
}
