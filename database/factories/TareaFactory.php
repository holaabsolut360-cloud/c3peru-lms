<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tarea>
 */
class TareaFactory extends Factory
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
            'titulo' => fake()->sentence(5),
            'instrucciones' => fake()->paragraph(),
            'fecha_entrega' => now()->addWeek(),
            'publicada' => true,
        ];
    }
}
