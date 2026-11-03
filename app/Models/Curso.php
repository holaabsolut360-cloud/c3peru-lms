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
            'aprendizajes' => 'array',
            'temario' => 'array',
            'preguntas' => 'array',
            'testimonios' => 'array',
            'incluye' => 'array',
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

    /**
     * @return array<int, string>
     */
    public function aprendizajesLista(): array
    {
        return $this->textos('aprendizajes');
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function temarioLista(): array
    {
        return $this->pares('temario', ['titulo', 'descripcion']);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function preguntasLista(): array
    {
        return $this->pares('preguntas', ['pregunta', 'respuesta']);
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function testimoniosLista(): array
    {
        return $this->pares('testimonios', ['nombre', 'texto']);
    }

    /**
     * @return array<int, string>
     */
    public function incluyeLista(): array
    {
        $propios = $this->textos('incluye');
        if ($propios !== []) {
            return $propios;
        }

        return [
            'Acceso al aula cuando el comprobante queda aprobado',
            'Certificado verificable por código o DNI',
        ];
    }

    public function textoLineas(string $campo): string
    {
        $valor = $this->{$campo};
        if (! is_array($valor)) {
            return '';
        }

        $lineas = [];
        foreach ($valor as $item) {
            if (is_string($item) && trim($item) !== '') {
                $lineas[] = $item;
            } elseif (is_array($item)) {
                $lineas[] = implode(' || ', array_map(fn ($parte) => (string) $parte, array_values($item)));
            }
        }

        return implode("\n", $lineas);
    }

    public function inicioVisible(): string
    {
        $inicio = trim((string) $this->fecha_inicio);

        return $inicio !== '' ? $inicio : (trim((string) $this->fechas) !== '' ? (string) $this->fechas : 'Por confirmar');
    }

    public function datoVisible(?string $valor): string
    {
        $texto = trim((string) $valor);

        return $texto !== '' ? $texto : 'Por confirmar';
    }

    /**
     * @return array<int, string>
     */
    private function textos(string $campo): array
    {
        $valor = $this->{$campo};
        if (! is_array($valor)) {
            return [];
        }

        return array_values(array_filter(array_map(
            fn ($item) => is_string($item) ? trim($item) : '',
            $valor
        )));
    }

    /**
     * @param  array<int, string>  $claves
     * @return array<int, array<string, string>>
     */
    private function pares(string $campo, array $claves): array
    {
        $valor = $this->{$campo};
        if (! is_array($valor)) {
            return [];
        }

        $salida = [];
        foreach ($valor as $item) {
            if (! is_array($item)) {
                continue;
            }
            $fila = [];
            foreach ($claves as $clave) {
                $fila[$clave] = trim((string) ($item[$clave] ?? ''));
            }
            if (implode('', $fila) !== '') {
                $salida[] = $fila;
            }
        }

        return $salida;
    }

    public function precioFormateado(): string
    {
        if (! $this->sePuedeComprar()) {
            return 'Consultar por WhatsApp';
        }

        return 'S/ '.number_format((float) $this->precio, 2, '.', ',');
    }
}
