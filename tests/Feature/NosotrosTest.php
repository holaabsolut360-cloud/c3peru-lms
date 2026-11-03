<?php

namespace Tests\Feature;

use App\Models\CifraComunidad;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NosotrosTest extends TestCase
{
    use RefreshDatabase;

    public function test_nosotros_usa_los_textos_reales_y_no_inventa_cifras(): void
    {
        $this->seed();

        $respuesta = $this->get(route('nosotros'));
        $respuesta->assertOk();
        $respuesta->assertSee('Creamos experiencias únicas', false);
        $respuesta->assertSee('C3 Peru SAC es tu aliado estratégico', false);
        $respuesta->assertSee('Liderando la Excelencia Industrial en Latinoamérica', false);
        $respuesta->assertSee('NTP ISO/IEC 17024', false);
        $respuesta->assertSee('Ana Castillo');
        $respuesta->assertSee('Luis Chirinos');
        $respuesta->assertSee('Ysabel Torres');
        $respuesta->assertSee('https://www.linkedin.com/in/anamariacastillovasquez', false);
        $respuesta->assertSee('Texto propuesto, por confirmar con el cliente');
        $respuesta->assertSee('POR CONFIRMAR');
        $respuesta->assertSee('Forma parte de nuestra comunidad');
        $respuesta->assertSee('Entrenamos en tu unidad, con tus equipos.');
        $respuesta->assertDontSee('1,200');
        $respuesta->assertDontSee('500 empresas');

        $this->get('/equipo')->assertRedirect(route('nosotros').'#equipo');

        $home = $this->get('/');
        $home->assertOk()->assertSee('Forma parte de nuestra comunidad');
        $this->assertStringContainsString(route('nosotros').'#compromiso', $home->getContent());
        $this->assertStringContainsString(route('nosotros').'#equipo', $home->getContent());
    }

    public function test_el_admin_puede_corregir_una_cifra_sin_publicar_un_numero_falso_por_defecto(): void
    {
        $this->seed();
        $admin = User::factory()->admin()->create();
        $cifra = CifraComunidad::query()->where('etiqueta', 'Cursos')->first();
        $this->assertSame('POR CONFIRMAR', $cifra->valor);

        $this->actingAs($admin)->put(route('admin.comunidad.cifras.actualizar', $cifra), [
            'etiqueta' => 'Cursos',
            'valor' => 'POR CONFIRMAR',
            'orden' => 2,
            'publicado' => '1',
        ])->assertRedirect();

        $this->assertSame('POR CONFIRMAR', $cifra->fresh()->valor);
    }
}
