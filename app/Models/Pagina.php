<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

        $html = $convertidor->convert($texto)->getContent();

        return preg_replace_callback('/<h([1-3])>(.*?)<\/h\1>/', function (array $coincidencia): string {
            $id = Str::slug(strip_tags($coincidencia[2]));

            return '<h'.$coincidencia[1].' id="'.$id.'">'.$coincidencia[2].'</h'.$coincidencia[1].'>';
        }, $html) ?? $html;
    }
}
