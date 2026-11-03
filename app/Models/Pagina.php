<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\CommonMark\CommonMarkConverter;

class Pagina extends Model
{
    protected $table = 'paginas';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'en_sitemap' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function html(): string
    {
        $marca = ConfiguracionApariencia::actual();
        $texto = strtr($this->cuerpo, [
            '{{razon_social}}' => (string) $marca->razon_social,
            '{{ruc}}' => (string) $marca->ruc,
            '{{correo}}' => (string) $marca->correo,
            '{{telefono}}' => (string) $marca->telefono,
            '{{direccion}}' => (string) $marca->direccion,
            '{{nombre_comercial}}' => (string) $marca->nombre_comercial,
            '{{sitio}}' => (string) $marca->sitio,
        ]);

        $convertidor = new CommonMarkConverter([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);

        return $convertidor->convert($texto)->getContent();
    }
}
