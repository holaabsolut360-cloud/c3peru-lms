<?php

namespace Tests\Feature;

use App\Models\Certificado;
use App\Models\Reclamacion;
use App\Models\User;
use App\Notifications\RestablecerContrasena;
use App\Support\LoginIntentos;
use Database\Seeders\MarcaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class LoginYCertificadosTest extends TestCase
{
    use RefreshDatabase;

    public function test_tres_intentos_fallidos_ofrecen_recuperar_sin_revelar_si_el_correo_existe(): void
    {
        $alumno = User::factory()->create([
            'email' => 'alumno@c3peru.test',
            'password' => 'secreta-123',
        ]);
        $docente = User::factory()->docente()->create([
            'email' => 'docente@c3peru.test',
            'password' => 'secreta-123',
        ]);

        $conocido = $this->from(route('login'))->post(route('login.guardar'), [
            'email' => $alumno->email,
            'password' => 'incorrecta',
        ]);
        $desconocido = $this->from(route('login'))->post(route('login.guardar'), [
            'email' => 'nadie@c3peru.test',
            'password' => 'incorrecta',
        ]);

        $conocido->assertSessionHasErrors(['email' => LoginIntentos::MENSAJE_CREDENCIALES]);
        $desconocido->assertSessionHasErrors(['email' => LoginIntentos::MENSAJE_CREDENCIALES]);
        $conocido->assertSessionMissing('limite_login');

        $this->followingRedirects()->from(route('login'))->post(route('login.guardar'), [
            'email' => 'nadie@c3peru.test',
            'password' => 'otra',
        ])->assertSee(LoginIntentos::MENSAJE_CREDENCIALES)->assertDontSee('no existe');

        $this->from(route('login'))->post(route('login.guardar'), [
            'email' => $alumno->email,
            'password' => 'incorrecta',
        ]);
        $this->from(route('login'))->post(route('login.guardar'), [
            'email' => $alumno->email,
            'password' => 'incorrecta',
        ])->assertSessionHas('limite_login', true)
            ->assertSessionHasErrors(['email' => LoginIntentos::MENSAJE_LIMITE]);

        $this->from(route('login'))->post(route('login.guardar'), [
            'email' => $alumno->email,
            'password' => 'secreta-123',
        ])->assertSessionHas('limite_login', true);
        $this->assertGuest();

        $this->from(route('login'))->post(route('login.guardar'), [
            'email' => $docente->email,
            'password' => 'mala-1',
        ]);
        $this->from(route('login'))->post(route('login.guardar'), [
            'email' => $docente->email,
            'password' => 'mala-2',
        ]);
        $this->from(route('login'))->post(route('login.guardar'), [
            'email' => $docente->email,
            'password' => 'mala-3',
        ])->assertSessionHas('limite_login', true);

        $this->get(route('login'))->assertSee('Recuperar o cambiar contraseña');
    }

    public function test_la_recuperacion_no_revela_el_correo_y_limpia_el_bloqueo(): void
    {
        Notification::fake();
        $alumno = User::factory()->create([
            'email' => 'alumno@c3peru.test',
            'password' => 'secreta-123',
        ]);

        foreach (range(1, 3) as $intento) {
            $this->post(route('login.guardar'), [
                'email' => $alumno->email,
                'password' => 'incorrecta',
            ]);
        }

        $mensaje = 'Si el correo está registrado, te enviaremos instrucciones para restablecer la contraseña.';
        $this->post(route('password.email'), ['email' => $alumno->email])->assertSessionHas('estado', $mensaje);
        $this->post(route('password.email'), ['email' => 'otro@c3peru.test'])->assertSessionHas('estado', $mensaje);
        Notification::assertSentTo($alumno, RestablecerContrasena::class);
        Notification::assertCount(1);

        $token = Password::broker()->createToken($alumno);
        $this->post(route('password.update'), [
            'token' => $token,
            'email' => $alumno->email,
            'password' => 'clave-nueva-99',
            'password_confirmation' => 'clave-nueva-99',
        ])->assertRedirect(route('login'));

        $this->assertTrue(Hash::check('clave-nueva-99', $alumno->fresh()->password));
        $this->post(route('login.guardar'), [
            'email' => $alumno->email,
            'password' => 'clave-nueva-99',
        ])->assertRedirect(route('aula.inicio'));
        $this->assertAuthenticatedAs($alumno);
    }

    public function test_el_pdf_del_certificado_responde_al_dueno_y_no_a_otro_alumno(): void
    {
        $this->seed(MarcaSeeder::class);
        $certificado = Certificado::factory()->create([
            'codigo' => 'C3-2026-ZX98QW',
            'nombre' => 'Ana Castillo',
        ]);
        $otro = User::factory()->create();

        $this->actingAs($otro)->get(route('certificados.pdf', $certificado))->assertForbidden();

        $respuesta = $this->actingAs($certificado->usuario)->get(route('certificados.pdf', $certificado));
        $respuesta->assertOk();
        $this->assertStringContainsString('pdf', (string) $respuesta->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF', $respuesta->getContent());
    }

    public function test_verifica_por_codigo_y_por_dni_con_respuesta_minima(): void
    {
        $certificado = Certificado::factory()->create([
            'nombre' => 'Ana Castillo',
            'documento_tipo' => 'dni',
            'documento_numero' => '12345678',
            'curso_titulo' => 'Inspección Visual',
            'codigo' => 'C3-2026-AB12CD',
            'emitido_en' => '2026-03-15 10:00:00',
            'estado' => 'vigente',
        ]);

        $this->get('/verificar-certificado?dni=12345678')
            ->assertOk()
            ->assertDontSee('Ana C');

        $porCodigo = $this->post(route('certificados.verificar'), ['consulta' => 'c3-2026-ab12cd']);
        $porCodigo->assertOk()->assertSee('Ana C******')->assertSee('Inspección Visual')->assertSee('15/03/2026')->assertSee('Vigente');
        $porCodigo->assertDontSee('12345678')->assertDontSee($certificado->usuario->email);

        $this->post(route('certificados.verificar'), ['consulta' => '12.345.678'])
            ->assertOk()
            ->assertSee('Ana C******')
            ->assertDontSee('12345678');
    }

    public function test_la_verificacion_no_lee_el_libro_de_reclamaciones_y_limita_intentos(): void
    {
        Reclamacion::query()->create([
            'codigo' => 'C3-LR-2026-0007',
            'nombre' => 'Luis',
            'primer_apellido' => 'Quispe',
            'tipo_documento' => 'dni',
            'numero_documento' => '87654321',
            'celular' => '999888777',
            'departamento' => 'LIMA',
            'provincia' => 'Lima',
            'distrito' => 'La Molina',
            'direccion' => 'Los Fresnos 123',
            'correo' => 'luis@example.com',
            'es_menor' => false,
            'tipo' => 'queja',
            'tipo_consumo' => 'servicio',
            'numero_pedido' => 'PED-1',
            'fecha_reclamo' => '2026-11-01',
            'descripcion_bien' => 'Curso de izaje',
            'detalle' => 'TEXTO SECRETO DEL RECLAMO',
            'pedido_cliente' => 'Quiero una respuesta',
            'acepta_privacidad' => true,
        ]);

        $this->post(route('certificados.verificar'), ['consulta' => '87654321'])
            ->assertOk()
            ->assertSee('No encontramos un certificado')
            ->assertDontSee('TEXTO SECRETO DEL RECLAMO');

        $fuente = file_get_contents(app_path('Http/Controllers/CertificadoVerificacionController.php'));
        $this->assertStringNotContainsString('Reclamacion', $fuente);
        $this->assertStringNotContainsString('libro_reclamaciones', $fuente);

        Cache::flush();

        foreach (range(1, 8) as $intento) {
            $this->from(route('certificados.formulario'))
                ->post(route('certificados.verificar'), ['consulta' => '00000000'])
                ->assertOk();
        }

        $this->from(route('certificados.formulario'))
            ->post(route('certificados.verificar'), ['consulta' => '00000000'])
            ->assertStatus(429);
    }
}
