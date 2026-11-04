<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompraYMatriculaTest extends TestCase
{
    use RefreshDatabase;

    public function test_el_curso_sin_precio_no_se_compra_y_ofrece_whatsapp(): void
    {
        $curso = Curso::factory()->consultar()->create(['titulo' => 'Inspección de aparejos']);

        $this->get(route('cursos.ver', $curso))
            ->assertOk()
            ->assertSee('Consultar por WhatsApp')
            ->assertDontSee('Inscribirme con comprobante');

        $alumno = User::factory()->create();
        $this->actingAs($alumno)
            ->post(route('compra.guardar', $curso), [
                'comprobante' => UploadedFile::fake()->image('voucher.jpg'),
            ])
            ->assertForbidden();

        $this->assertDatabaseCount('pagos', 0);
    }

    public function test_la_inscripcion_muestra_el_curso_y_solicita_el_comprobante(): void
    {
        $alumno = User::factory()->create();
        $curso = Curso::factory()->create(['titulo' => 'Inspección Visual']);

        $this->actingAs($alumno)
            ->get(route('compra.crear', $curso))
            ->assertOk()
            ->assertSee('Completa tu inscripción')
            ->assertSee('Inspección Visual')
            ->assertSee('Selecciona tu comprobante')
            ->assertSee('name="referencia"', false);
    }

    public function test_el_comprobante_queda_pendiente_y_solo_el_admin_matricula(): void
    {
        Storage::fake('comprobantes');
        $alumno = User::factory()->create();
        $docente = User::factory()->docente()->create();
        $admin = User::factory()->admin()->create();
        $curso = Curso::factory()->create(['precio' => 970, 'titulo' => 'Inspección Visual']);

        $this->actingAs($alumno)
            ->post(route('compra.guardar', $curso), [
                'comprobante' => UploadedFile::fake()->image('voucher.jpg'),
                'referencia' => 'OP-100',
            ])
            ->assertRedirect(route('aula.pagos'));

        $pago = Pago::query()->firstOrFail();
        $this->assertSame('pendiente', $pago->estado);
        $this->assertSame('970.00', $pago->monto);
        Storage::disk('comprobantes')->assertExists($pago->comprobante_path);
        $this->assertFileDoesNotExist(public_path('storage/'.$pago->comprobante_path));
        $this->assertDatabaseCount('matriculas', 0);

        $this->actingAs($alumno)->post(route('admin.pagos.aprobar', $pago))->assertForbidden();
        $this->actingAs($docente)->post(route('admin.pagos.aprobar', $pago))->assertForbidden();
        $this->assertFalse(Gate::forUser($alumno)->allows('aprobar-pagos'));
        $this->assertFalse(Gate::forUser($docente)->allows('aprobar-pagos'));
        $this->assertTrue(Gate::forUser($admin)->allows('aprobar-pagos'));

        $this->actingAs($admin)->post(route('admin.pagos.aprobar', $pago))->assertRedirect();

        $pago->refresh();
        $this->assertSame('aprobado', $pago->estado);
        $this->assertSame($admin->id, $pago->aprobado_por);
        $this->assertDatabaseHas('matriculas', [
            'user_id' => $alumno->id,
            'curso_id' => $curso->id,
            'estado' => 'activa',
        ]);

        $this->actingAs($alumno)->get(route('aula.curso', $curso))->assertOk()->assertSee('Inspección Visual');
        $this->actingAs($docente)->get(route('aula.curso', $curso))->assertForbidden();
    }

    public function test_rechaza_archivos_que_no_son_imagen_o_pdf(): void
    {
        Storage::fake('comprobantes');
        $alumno = User::factory()->create();
        $curso = Curso::factory()->create(['precio' => 800]);

        $this->actingAs($alumno)
            ->post(route('compra.guardar', $curso), [
                'comprobante' => UploadedFile::fake()->create('notas.txt', 20, 'text/plain'),
            ])
            ->assertSessionHasErrors('comprobante');

        $this->assertDatabaseCount('pagos', 0);
    }

    public function test_el_html_de_la_leccion_se_muestra_escapado(): void
    {
        $alumno = User::factory()->create();
        $curso = Curso::factory()->create();
        $curso->lecciones()->create([
            'titulo' => 'Material',
            'contenido' => '<script>alert(1)</script><b>hola</b>',
            'orden' => 1,
        ]);
        Matricula::query()->create([
            'user_id' => $alumno->id,
            'curso_id' => $curso->id,
            'estado' => 'activa',
        ]);

        $this->actingAs($alumno)
            ->get(route('aula.curso', $curso))
            ->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false);
    }
}
