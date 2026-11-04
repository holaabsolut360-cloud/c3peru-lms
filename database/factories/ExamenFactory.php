<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Examen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Examen>
use App\Models\Curso;
 */
class ExamenFactory extends Factory
{
    /**
            'curso_id' => Curso::factory(),
            'titulo' => fake()->sentence(4),
            'instrucciones' => 'Responde todas las preguntas.',
            'duracion_minutos' => 30,
            'puntaje_aprobatorio' => 14,
            'intentos_permitidos' => 1,
            'disponible' => true,
            'fecha_inicio' => null,
            'fecha_fin' => null,
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'curso_id' => Curso::factory(),
            'titulo' => fake()->sentence(4),
            'instrucciones' => 'Responde todas las preguntas.',
            'duracion_minutos' => 30,
            'puntaje_aprobatorio' => 14,
            'intentos_permitidos' => 1,
            'disponible' => true,
            'fecha_inicio' => null,
            'fecha_fin' => null,
        ];
    }
}
