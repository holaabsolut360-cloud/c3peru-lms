<?php

namespace Tests\Feature;

use App\Models\Curso;
use FilesystemIterator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Tests\TestCase;

class SitioPublicoTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_home_expone_marca_icono_y_descripcion(): void
    {
        $this->seed();

        $respuesta = $this->get('/');
        $respuesta->assertOk();
        $respuesta->assertSee('rel="icon"', false);
        $respuesta->assertSee('property="og:description"', false);
        $respuesta->assertSee('CAPACITACION-CERTIFICACION-CALIDAD PERU S.A.C.');
        $respuesta->assertSee('20605518860');
        $respuesta->assertSee('class="boton-whatsapp', false);
        $respuesta->assertDontSee('tarjeta de crédito');
        $respuesta->assertDontSee('contact@mysite.com');
        $this->assertSame('America/Lima', config('app.timezone'));
        $this->assertFalse(config('app.debug'));
    }

    public function test_el_catalogo_tiene_34_cursos_y_los_legales_salen_en_el_sitemap(): void
    {
        $this->seed();

        $this->assertSame(34, Curso::query()->count());
        $this->assertSame(10, Curso::query()->whereNotNull('precio')->count());
        $this->assertSame(24, Curso::query()->whereNull('precio')->count());

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: https://c3peru.com/sitemap.xml');

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertSee('https://c3peru.com/paginas/aviso-legal', false)
            ->assertSee('https://c3peru.com/paginas/politica-de-privacidad', false)
            ->assertSee('https://c3peru.com/paginas/politica-de-cookies', false)
            ->assertSee('https://c3peru.com/paginas/devoluciones-y-reembolso', false)
            ->assertSee('https://c3peru.com/paginas/terminos-y-condiciones', false)
            ->assertSee('https://c3peru.com/libro-de-reclamaciones', false);

        $this->get(route('paginas.ver', 'aviso-legal'))
            ->assertOk()
            ->assertSee('20605518860')
            ->assertSee('ana.castillo@c3peru.com')
            ->assertDontSee('contact@mysite.com');
    }

    public function test_contacto_guarda_empresa_y_datos_de_cotizacion(): void
    {
        $this->get(route('contacto'))
            ->assertOk()
            ->assertSee('Contacto y cotización corporativa')
            ->assertSee('name="empresa"', false);

        $this->from(route('contacto'))
            ->post(route('contacto.guardar'), [
                'nombre' => 'María Pérez',
                'empresa' => 'Operaciones Andinas S.A.C.',
                'correo' => 'maria@empresa.test',
                'telefono' => '+51 999 123 456',
                'especialidad' => 'Izaje y Manejo de Cargas',
                'modalidad' => 'INHOUSE',
                'mensaje' => 'Cotización para un equipo de operación.',
            ])
            ->assertRedirect(route('contacto'))
            ->assertSessionHas('estado');

        $this->assertDatabaseHas('mensajes_contacto', [
            'nombre' => 'María Pérez',
            'empresa' => 'Operaciones Andinas S.A.C.',
            'correo' => 'maria@empresa.test',
            'especialidad' => 'Izaje y Manejo de Cargas',
            'modalidad' => 'INHOUSE',
        ]);
    }

    public function test_no_hay_rutas_de_membresia_ni_marcas_ajenas_ni_hex_dispersos(): void
    {
        $this->get('/membresias')->assertNotFound();
        $this->get('/planes')->assertNotFound();
        $this->get('/para-colegios')->assertNotFound();

        $ejemplo = file_get_contents(base_path('.env.example'));
        $this->assertStringContainsString('APP_DEBUG=false', $ejemplo);
        $this->assertStringContainsString('CACHE_STORE=file', $ejemplo);
        $this->assertStringContainsString('QUEUE_CONNECTION=sync', $ejemplo);
        $this->assertStringContainsString('APP_TIMEZONE=America/Lima', $ejemplo);
        $this->assertStringContainsString('ADMIN_EMAIL=', $ejemplo);
        $this->assertDoesNotMatchRegularExpression('/ADMIN_PASSWORD=.+/', $ejemplo);

        $prohibido = ['perucapacitate', 'ceprepol', 'cursotube', 'absolut360', 'contact@mysite.com', 'tarjeta de crédito', 'tarjeta de credito'];
        $hex = ['#24336b', '#184691', '#3fb7df', '#0b1126', '#06132b', '#25d366'];
        $permitidoHex = realpath(config_path('marca.php'));

        $raices = [app_path(), resource_path(), database_path(), config_path(), base_path('routes'), public_path()];
        foreach ($raices as $raiz) {
            $this->assertTextosLimpios($raiz, $prohibido, $hex, $permitidoHex);
        }
    }

    private function assertTextosLimpios(string $raiz, array $prohibido, array $hex, string $permitidoHex): void
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($raiz, FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $archivo) {
            if (! $archivo instanceof SplFileInfo || ! $archivo->isFile()) {
                continue;
            }
            $ruta = $archivo->getPathname();
            if ($this->seOmite($ruta) || ! $this->esTexto($archivo)) {
                continue;
            }
            $contenido = strtolower((string) file_get_contents($ruta));
            foreach ($prohibido as $termino) {
                $this->assertStringNotContainsString($termino, $contenido, $ruta);
            }
            if (realpath($ruta) === $permitidoHex || str_ends_with(strtolower($ruta), '.md')) {
                continue;
            }
            foreach ($hex as $color) {
                $this->assertStringNotContainsString($color, $contenido, $ruta);
            }
        }
    }

    private function seOmite(string $ruta): bool
    {
        $ruta = str_replace('\\', '/', $ruta);

        foreach (['/vendor/', '/node_modules/', '/public/build/', '/storage/', '/.git/'] as $fragmento) {
            if (str_contains($ruta, $fragmento)) {
                return true;
            }
        }

        return false;
    }

    private function esTexto(SplFileInfo $archivo): bool
    {
        return in_array(strtolower($archivo->getExtension()), ['php', 'css', 'js', 'json', 'md', 'xml', 'blade', 'txt', 'example'], true);
    }
}
