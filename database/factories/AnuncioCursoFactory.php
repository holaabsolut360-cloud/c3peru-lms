<?php

namespace Database\Factories;

use App\Models\AnuncioCurso;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnuncioCurso>
 */
class AnuncioCursoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'curso_id' => Curso::factory(),
            'docente_id' => User::factory()->docente(),
            'titulo' => fake()->sentence(4),
            'contenido' => fake()->paragraph(),
            'enviar_email' => true,
            'enviar_whatsapp' => false,
            'publicado_en' => now(),
        ];
    }
}
