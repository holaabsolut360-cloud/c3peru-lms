<?php

namespace App\Models;

use Database\Factories\CursoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    /** @use HasFactory<CursoFactory> */
    use HasFactory;

    protected $table = 'cursos';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'incluye_igv' => 'boolean',
            'publicado' => 'boolean',
            'destacado' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    public function lecciones(): HasMany
    {
        return $this->hasMany(Leccion::class);
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function sePuedeComprar(): bool
    {
        return $this->precio !== null && (float) $this->precio > 0;
    }

    public function imagenPortada(): string
    {
        if (is_string($this->portada) && $this->portada !== '') {
            return $this->portada;
        }

        return match ($this->categoria?->slug) {
            'izaje' => '/marca/izajes.jpg',
            'end-ndt' => '/marca/end.jpg',
            'oil-gas' => '/marca/oilgas.jpg',
            'mantenimiento' => '/marca/mantenimiento.jpg',
            default => '/marca/hero.jpg',
        };
    }

    public function precioFormateado(): string
    {
        if (! $this->sePuedeComprar()) {
            return 'Consultar por WhatsApp';
        }

        return 'S/ '.number_format((float) $this->precio, 2, '.', ',');
    }
}
