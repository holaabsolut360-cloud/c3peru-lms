<?php

namespace Tests\Feature;

use App\Models\ClaseEnVivo;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClasesEnVivoTest extends TestCase
{
    use RefreshDatabase;

    public function test_docente_programa_una_clase_para_un_curso_asignado(): void
    {
        $docente = User::factory()->docente()->create();
        $curso = Curso::factory()->create(['docente_id' => $docente->id]);
        $leccion = $curso->lecciones()->create([
            'titulo' => 'Fundamentos',
            'contenido' => 'Contenido de prueba',
            'orden' => 1,
        ]);
        $fechaInicio = now()->addDay()->startOfMinute();
        $fechaFin = $fechaInicio->copy()->addHour();

        $this->actingAs($docente)
            ->get(route('docente.clases.crear', $curso))
            ->assertOk()
            ->assertSee('Programar clase en vivo')
            ->assertSee('Fundamentos');

        $this->actingAs($docente)
            ->post(route('docente.clases.guardar', $curso), [
                'leccion_id' => $leccion->id,
                'titulo' => 'Sesión de práctica',
                'descripcion' => 'Revisión de equipos y procedimientos.',
                'fecha_inicio' => $fechaInicio->format('Y-m-d\TH:i'),
                'fecha_fin' => $fechaFin->format('Y-m-d\TH:i'),
            ])
            ->assertRedirect(route('docente.inicio'))
            ->assertSessionHas('estado');

        $this->assertDatabaseHas('clases_en_vivo', [
            'curso_id' => $curso->id,
            'leccion_id' => $leccion->id,
            'docente_id' => $docente->id,
            'titulo' => 'Sesión de práctica',
            'estado' => 'programada',
        ]);
        $clase = ClaseEnVivo::query()->firstOrFail();
        $this->assertStringStartsWith('mock-meeting-', $clase->teams_meeting_id);
        $this->assertStringContainsString('/l/meetup-join/mock-', $clase->teams_join_url);
    }

    public function test_docente_no_puede_programar_clase_en_curso_ajeno(): void
    {
        $docente = User::factory()->docente()->create();
        $otroDocente = User::factory()->docente()->create();
        $curso = Curso::factory()->create(['docente_id' => $otroDocente->id]);

        $this->actingAs($docente)
            ->get(route('docente.clases.crear', $curso))
            ->assertForbidden();
    }

    public function test_no_acepta_leccion_de_otro_curso_ni_enlace_teams_externo(): void
    {
        $docente = User::factory()->docente()->create();
        $curso = Curso::factory()->create(['docente_id' => $docente->id]);
        $otroCurso = Curso::factory()->create();
        $leccion = $otroCurso->lecciones()->create([
            'titulo' => 'Lección ajena',
            'contenido' => 'Contenido',
            'orden' => 1,
        ]);
        $fechaInicio = now()->addDay()->startOfMinute();

        $this->actingAs($docente)
            ->from(route('docente.clases.crear', $curso))
            ->post(route('docente.clases.guardar', $curso), [
                'leccion_id' => $leccion->id,
                'titulo' => 'Sesión de práctica',
                'fecha_inicio' => $fechaInicio->format('Y-m-d\TH:i'),
                'fecha_fin' => $fechaInicio->copy()->addHour()->format('Y-m-d\TH:i'),
            ])
            ->assertSessionHasErrors('leccion_id');

        $this->assertDatabaseCount('clases_en_vivo', 0);
    }

    public function test_alumno_matriculado_ve_clases_proximas_de_su_curso(): void
    {
        $alumno = User::factory()->create();
        $docente = User::factory()->docente()->create();
        $curso = Curso::factory()->create(['docente_id' => $docente->id]);
        $otroCurso = Curso::factory()->create();
        ClaseEnVivo::factory()->for($curso)->create([
            'docente_id' => $docente->id,
            'titulo' => 'Práctica de inspección',
            'teams_meeting_id' => 'mock-meeting-fixture',
            'teams_join_url' => 'https://teams.microsoft.com/l/meetup-join/demo',
        ]);
        ClaseEnVivo::factory()->for($curso)->create([
            'docente_id' => $docente->id,
            'titulo' => 'Sesión cancelada',
            'estado' => 'cancelada',
        ]);
        ClaseEnVivo::factory()->for($otroCurso)->create([
            'titulo' => 'Clase de otro curso',
        ]);
        Matricula::query()->create([
            'user_id' => $alumno->id,
            'curso_id' => $curso->id,
            'estado' => 'activa',
        ]);

        $this->actingAs($alumno)
            ->get(route('aula.curso', $curso))
            ->assertOk()
            ->assertSee('Próximas clases en vivo')
            ->assertSee('Práctica de inspección')
            ->assertSee('Teams simulado')
            ->assertDontSee('Sesión cancelada')
            ->assertDontSee('Clase de otro curso');
    }

    public function test_inicio_del_aula_lista_clases_de_cursos_activos(): void
    {
        $alumno = User::factory()->create();
        $cursoActivo = Curso::factory()->create();
        $cursoPendiente = Curso::factory()->create();
        ClaseEnVivo::factory()->create([
            'curso_id' => $cursoActivo->id,
            'titulo' => 'Clase visible del aula',
        ]);
        ClaseEnVivo::factory()->create([
            'curso_id' => $cursoPendiente->id,
            'titulo' => 'Clase no matriculada',
        ]);
        Matricula::query()->create([
            'user_id' => $alumno->id,
            'curso_id' => $cursoActivo->id,
            'estado' => 'activa',
        ]);
        Matricula::query()->create([
            'user_id' => $alumno->id,
            'curso_id' => $cursoPendiente->id,
            'estado' => 'pendiente',
        ]);

        $this->actingAs($alumno)
            ->get(route('aula.inicio'))
            ->assertOk()
            ->assertSee('Clase visible del aula')
            ->assertDontSee('Clase no matriculada');
    }
}
