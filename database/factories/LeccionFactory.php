<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Leccion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Leccion>
 */
class LeccionFactory extends Factory
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
            'titulo' => fake()->sentence(4),
            'contenido' => fake()->paragraph(),
            'orden' => 1,
        ];
    }
}
