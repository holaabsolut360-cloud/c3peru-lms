<?php

namespace Tests\Feature;

use App\Livewire\Examenes\RealizarExamen;
use App\Models\Curso;
use App\Models\Examen;
use App\Models\Matricula;
use App\Models\Pregunta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExamenesTest extends TestCase
{
    use RefreshDatabase;

    public function test_califica_respuestas_en_servidor_y_respeta_limite_de_intentos(): void
    {
        $alumno = User::factory()->create();
        $curso = Curso::factory()->create();
        $examen = Examen::factory()->for($curso)->create([
            'titulo' => 'Examen de fundamentos',
            'intentos_permitidos' => 1,
        ]);
        $pregunta = Pregunta::factory()->for($examen)->create([
            'enunciado' => '¿Cuál es la respuesta correcta?',
            'opciones' => ['A' => 'Opción correcta', 'B' => 'Opción incorrecta', 'C' => 'Distractor 1', 'D' => 'Distractor 2'],
            'respuesta_correcta' => 'A',
            'puntaje' => 2,
        ]);
        Matricula::query()->create([
            'user_id' => $alumno->id,
            'curso_id' => $curso->id,
            'estado' => 'activa',
        ]);

        $this->actingAs($alumno);
        Livewire::test(RealizarExamen::class, ['examen' => $examen])
            ->assertDontSee('respuesta_correcta')
            ->call('iniciarExamen')
            ->assertSee('Opción correcta')
            ->assertDontSee('respuesta_correcta')
            ->call('responder', $pregunta->id, 'A')
            ->call('finalizarExamen')
            ->assertSet('notaFinal', 20.0)
            ->assertSet('aprobado', true);

        $this->assertDatabaseHas('examen_intentos', [
            'examen_id' => $examen->id,
            'user_id' => $alumno->id,
            'puntaje_total' => 2,
            'nota_final' => '20.00',
            'aprobado' => true,
        ]);

        Livewire::test(RealizarExamen::class, ['examen' => $examen])
            ->assertSet('intentosAgotados', true)
            ->assertSee('Intentos completados');
    }

    public function test_no_muestra_examen_a_usuario_sin_matricula_activa(): void
    {
        $alumno = User::factory()->create();
        $examen = Examen::factory()->create();

        $this->actingAs($alumno)
            ->get(route('aula.examenes.realizar', $examen))
            ->assertForbidden();
    }
}
