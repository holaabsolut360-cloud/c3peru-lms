<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class ConfiguracionApariencia extends Model
{
    protected $table = 'configuracion_apariencia';

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saved(fn () => static::olvidar());
        static::deleted(fn () => static::olvidar());
    }

    public static function olvidar(): void
    {
        Cache::forget('configuracion_apariencia');
    }

    public static function actual(): self
    {
        $cacheada = Cache::get('configuracion_apariencia');
        if ($cacheada instanceof self) {
            return $cacheada;
        }

        try {
            if (! Schema::hasTable('configuracion_apariencia')) {
                return static::desdeConfiguracion();
            }
            $fila = static::query()->first();
        } catch (Throwable) {
            return static::desdeConfiguracion();
        }

        if ($fila === null) {
            return static::desdeConfiguracion();
        }

        Cache::put('configuracion_apariencia', $fila, 300);

        return $fila;
    }

    public static function desdeConfiguracion(): self
    {
        $modelo = new static;
        $modelo->forceFill(config('marca'));

        return $modelo;
    }

    /**
     * @return array<string, string>
     */
    public function variablesCss(): array
    {
        $defecto = config('marca');

        return [
            '--c3-primario' => $this->colorCss($this->color_primario, $defecto['color_primario']),
            '--c3-secundario' => $this->colorCss($this->color_secundario, $defecto['color_secundario']),
            '--c3-acento' => $this->colorCss($this->color_acento, $defecto['color_acento']),
            '--c3-fondo' => $this->colorCss($this->color_fondo, $defecto['color_fondo']),
            '--c3-fondo-alt' => $this->colorCss($this->color_fondo_alt, $defecto['color_fondo_alt']),
            '--c3-whatsapp' => $this->colorCss($this->color_whatsapp, $defecto['color_whatsapp']),
            '--c3-fuente-titulos' => $this->fuenteCss($this->fuente_titulos, $defecto['fuente_titulos']),
            '--c3-fuente-ui' => $this->fuenteCss($this->fuente_ui, $defecto['fuente_ui']),
            '--c3-fuente-texto' => $this->fuenteCss($this->fuente_texto, $defecto['fuente_texto']),
        ];
    }

    public function enlaceWhatsapp(?string $texto = null): string
    {
        $numero = preg_replace('/\D+/', '', (string) $this->whatsapp) ?: '';
        $url = 'https://wa.me/'.$numero;
        if ($texto) {
            $url .= '?text='.rawurlencode($texto);
        }

        return $url;
    }

    private function colorCss(?string $valor, string $defecto): string
    {
        return is_string($valor) && preg_match('/^#[0-9A-Fa-f]{6}$/', $valor) ? $valor : $defecto;
    }

    private function fuenteCss(?string $valor, string $defecto): string
    {
        return is_string($valor) && preg_match('/^[A-Za-z][A-Za-z0-9 \-]{0,40}$/', $valor)
            ? $valor
            : $defecto;
    }
}
