<?php

namespace Tests\Feature;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use App\Models\Video;
use App\Services\VideoProtectionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class VideosProtegidosTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_guarda_el_archivo_en_almacenamiento_privado(): void
    {
        Storage::fake('local');
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $curso = Curso::factory()->create();
        $leccion = $curso->lecciones()->create([
            'titulo' => 'Introducción',
            'contenido' => 'Material',
            'orden' => 1,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.cursos.videos.guardar', $curso), [
                'leccion_id' => $leccion->id,
                'titulo' => 'Video de introducción',
                'video' => UploadedFile::fake()->create('introduccion.mp4', 100, 'video/mp4'),
            ])
            ->assertRedirect();

        $video = Video::query()->firstOrFail();
        Storage::disk('local')->assertExists($video->archivo_path);
        Storage::disk('public')->assertMissing($video->archivo_path);
        $this->assertTrue($video->protegido);
    }

    public function test_alumno_matriculado_reproduce_video_con_url_firmada(): void
    {
        Storage::fake('local');
        $alumno = User::factory()->create();
        $curso = Curso::factory()->create();
        $leccion = $curso->lecciones()->create([
            'titulo' => 'Introducción',
            'contenido' => 'Material',
            'orden' => 1,
        ]);
        $video = Video::factory()->for($leccion)->create([
            'archivo_path' => 'videos/intro.mp4',
            'mime_type' => 'video/mp4',
        ]);
        Storage::disk('local')->put($video->archivo_path, 'contenido de video');
        Matricula::query()->create([
            'user_id' => $alumno->id,
            'curso_id' => $curso->id,
            'estado' => 'activa',
        ]);

        $this->actingAs($alumno)
            ->get(route('aula.curso', $curso))
            ->assertOk()
            ->assertSee('controlslist="nodownload"', false)
            ->assertSee($alumno->name)
            ->assertDontSee($video->archivo_path);

        $urlFirmada = app(VideoProtectionService::class)->generarUrlFirmada($video, $alumno);

        $respuesta = $this->actingAs($alumno)->get($urlFirmada);

        $respuesta->assertOk();
        $this->assertSame('video/mp4', $respuesta->headers->get('Content-Type'));
        $this->assertStringContainsString('no-store', $respuesta->headers->get('Cache-Control'));
    }

    public function test_rechaza_url_expirada_y_usuario_sin_matricula_activa(): void
    {
        Storage::fake('local');
        $alumno = User::factory()->create();
        $otroAlumno = User::factory()->create();
        $curso = Curso::factory()->create();
        $leccion = $curso->lecciones()->create([
            'titulo' => 'Introducción',
            'contenido' => 'Material',
            'orden' => 1,
        ]);
        $video = Video::factory()->for($leccion)->create(['archivo_path' => 'videos/intro.mp4']);
        Storage::disk('local')->put($video->archivo_path, 'contenido de video');
        Matricula::query()->create([
            'user_id' => $alumno->id,
            'curso_id' => $curso->id,
            'estado' => 'activa',
        ]);
        $urlFirmada = app(VideoProtectionService::class)->generarUrlFirmada($video, $alumno);
        $urlExpirada = URL::temporarySignedRoute(
            'aula.videos.stream',
            now()->subMinute(),
            ['video' => $video->id, 'user_id' => $alumno->id],
        );

        $this->actingAs($otroAlumno)->get($urlFirmada)->assertForbidden();
        $this->actingAs($alumno)->get($urlExpirada)->assertForbidden();
    }
}
