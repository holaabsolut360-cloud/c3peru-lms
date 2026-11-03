<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Pagina;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function robots(): Response
    {
        $mapa = rtrim((string) config('app.url'), '/').'/sitemap.xml';
        $texto = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /aula',
            'Disallow: /docente',
            'Disallow: /comprobantes',
            'Sitemap: '.$mapa,
            '',
        ]);

        return response($texto, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap(): Response
    {
        $base = rtrim((string) config('app.url'), '/');
        $urls = [
            $base.'/',
            $base.'/cursos',
            $base.'/verificar-certificado',
            $base.'/contacto',
            $base.'/blog',
            $base.'/libro-de-reclamaciones',
        ];

        foreach (Pagina::query()->where('en_sitemap', true)->orderBy('orden')->get() as $pagina) {
            $urls[] = $base.'/paginas/'.$pagina->slug;
        }

        foreach (Curso::query()->where('publicado', true)->orderBy('orden')->get() as $curso) {
            $urls[] = $base.'/cursos/'.$curso->slug;
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls as $url) {
            $xml .= '  <url><loc>'.e($url).'</loc></url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
