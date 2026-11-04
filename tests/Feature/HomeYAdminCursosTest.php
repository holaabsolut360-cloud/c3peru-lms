<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomeYAdminCursosTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_portada_sigue_el_orden_y_el_menu_completo(): void
    {
        $this->seed();

        $respuesta = $this->get('/');
        $respuesta->assertOk();
        $html = $respuesta->getContent();
        $this->assertStringContainsString('id="menu-movil"', $html);
        $this->assertStringContainsString('Iniciar Sesión', $html);
        $this->assertStringContainsString('Certificados', $html);
        $this->assertStringContainsString('Contáctanos', $html);
        $this->assertStringContainsString('Visión + Misión', $html);
        $this->assertStringContainsString('Nuestro Compromiso', $html);
        $this->assertStringContainsString('aprende con expertos', $html);
        $this->assertStringContainsString('4 especialidades para la industria', $html);
        $this->assertStringContainsString('POR CONFIRMAR', $html);
        $this->assertStringContainsString('/marca/end.jpg', $html);

        $empresa = strpos($html, 'id="empresa"');
        $especialidades = strpos($html, 'id="especialidades"');
        $cursos = strpos($html, 'id="cursos"');
        $modalidades = strpos($html, 'id="modalidades"');
        $clientes = strpos($html, 'id="clientes"');
        $preguntas = strpos($html, 'id="preguntas"');
        $contacto = strpos($html, 'id="contacto"');
        $this->assertNotFalse($empresa);
        $this->assertTrue($empresa < $especialidades && $especialidades < $cursos && $cursos < $modalidades && $modalidades < $clientes && $clientes < $preguntas && $preguntas < $contacto);

        $curso = Curso::query()->whereNull('precio')->first();
        $ficha = $this->get(route('cursos.ver', $curso));
        $ficha->assertOk()->assertSee('Consultar por WhatsApp');
        $this->assertSame(1, substr_count($ficha->getContent(), 'boton-whatsapp'));
        $ficha->assertSee('Inscribirme')->assertSee('Lo que aprenderás')->assertSee('Verificable por código o DNI')->assertSee('POR CONFIRMAR');
    }

    public function test_el_catalogo_filtra_cursos_por_slug_de_especialidad(): void
    {
        $categoriaActiva = Categoria::factory()->create([
            'nombre' => 'Ensayos No Destructivos',
            'slug' => 'end-ndt',
        ]);
        $otraCategoria = Categoria::factory()->create([
            'nombre' => 'Izaje',
            'slug' => 'izaje',
        ]);
        Curso::factory()->for($categoriaActiva)->create(['titulo' => 'Inspección Visual']);
        Curso::factory()->for($otraCategoria)->create(['titulo' => 'Inspección de Aparejos']);

        $this->get(route('cursos.index', ['categoria' => $categoriaActiva->slug]))
            ->assertOk()
            ->assertSee('Ensayos No Destructivos')
            ->assertSee('Inspección Visual')
            ->assertDontSee('Inspección de Aparejos')
            ->assertSee('aria-current="page"', false);
    }

    public function test_el_catalogo_pagina_cursos_y_conserva_el_filtro(): void
    {
        $categoria = Categoria::factory()->create([
            'nombre' => 'Ensayos No Destructivos',
            'slug' => 'end-ndt',
        ]);

        for ($numeroCurso = 1; $numeroCurso <= 10; $numeroCurso++) {
            Curso::factory()->for($categoria)->create([
                'titulo' => $numeroCurso === 10 ? 'Curso pagina dos' : 'Curso pagina uno '.$numeroCurso,
                'slug' => 'curso-paginado-'.$numeroCurso,
                'orden' => $numeroCurso,
            ]);
        }

        $this->get(route('cursos.index', ['categoria' => $categoria->slug, 'page' => 2]))
            ->assertOk()
            ->assertSee('Curso pagina dos')
            ->assertDontSee('Curso pagina uno 1')
            ->assertSee('categoria=end-ndt&amp;page=1', false);
    }

    public function test_la_ficha_muestra_migas_de_pan_y_el_docente_asignado(): void
    {
        $categoria = Categoria::factory()->create();
        $docente = User::factory()->docente()->create(['name' => 'Docente de Prueba']);
        $curso = Curso::factory()->for($categoria)->create([
            'docente_id' => $docente->id,
            'instructor_nombre' => null,
            'instructor_cargo' => null,
        ]);

        $this->get(route('cursos.ver', $curso))
            ->assertOk()
            ->assertSee('aria-label="Miga de pan"', false)
            ->assertSee('Docente de Prueba')
            ->assertSee('Docente C3 Perú');
    }

    public function test_la_ficha_oculta_bloques_vacios_y_el_admin_guarda_el_detalle(): void
    {
        $categoria = Categoria::factory()->create();
        $curso = Curso::factory()->create([
            'categoria_id' => $categoria->id,
            'precio' => 500,
            'beneficio' => null,
            'aprendizajes' => null,
            'temario' => null,
            'testimonios' => null,
            'preguntas' => null,
        ]);
        Curso::factory()->create(['categoria_id' => $categoria->id, 'titulo' => 'Curso hermano']);

        $html = $this->get(route('cursos.ver', $curso))->assertOk()->assertSee('Inscribirme con comprobante')->getContent();
        $this->assertStringNotContainsString('Lo que aprenderás', $html);
        $this->assertStringNotContainsString('id="testimonios"', $html);
        $this->assertStringContainsString('Curso hermano', $html);
        $this->assertStringContainsString(route('compra.crear', $curso, false), $html);

        $admin = User::factory()->admin()->create();
        $this->actingAs($admin)->put(route('admin.cursos.actualizar', $curso), [
            'categoria_id' => $categoria->id,
            'titulo' => $curso->titulo,
            'nivel' => $curso->nivel,
            'duracion' => '10 hrs',
            'modalidad' => 'Virtual',
            'precio' => 500,
            'publicado' => '1',
            'aprendizajes' => "Identificar el alcance\nAplicar el procedimiento",
            'temario' => 'Introducción || POR CONFIRMAR',
        ])->assertRedirect(route('admin.cursos'));

        $curso->refresh();
        $this->assertSame(['Identificar el alcance', 'Aplicar el procedimiento'], $curso->aprendizajesLista());
        $this->assertSame('Introducción', $curso->temarioLista()[0]['titulo']);
    }

    public function test_el_admin_gestiona_cursos_y_el_404_es_de_marca(): void
    {
        Storage::fake('public');
        $this->seed();
        $admin = User::factory()->admin()->create();
        $alumno = User::factory()->create();
        $categoria = Categoria::query()->first();

        $this->get('/admin/cursos')->assertRedirect(route('login'));
        $this->actingAs($alumno)->get('/admin/cursos')->assertForbidden();

        $this->actingAs($admin)->get('/admin/cursos')->assertOk()->assertSee('Nuevo curso');
        $this->actingAs($admin)->post(route('admin.cursos.guardar'), [
            'categoria_id' => $categoria->id,
            'titulo' => 'Curso de prueba visual',
            'nivel' => 'Nivel demo',
            'duracion' => '8 hrs',
            'modalidad' => 'Virtual',
            'fechas' => 'Por confirmar',
            'certificacion' => 'POR CONFIRMAR',
            'precio' => 150,
            'publicado' => '1',
            'incluye_igv' => '1',
            'portada' => UploadedFile::fake()->image('portada.jpg'),
        ])->assertRedirect(route('admin.cursos'));

        $creado = Curso::query()->where('titulo', 'Curso de prueba visual')->first();
        $this->assertNotNull($creado);
        $this->assertNotNull($creado->portada);
        Storage::disk('public')->assertExists(str_replace('/storage/', '', $creado->portada));

        $this->get('/pagina-que-no-existe')
            ->assertNotFound()
            ->assertSee('No encontramos esta página')
            ->assertSee($this->app['config']->get('marca.nombre_comercial'));
    }
}
