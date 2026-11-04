<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Insignia;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Insignia>
 */
class InsigniaFactory extends Factory
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
            'nombre' => fake()->sentence(3),
            'descripcion' => fake()->paragraph(),
            'imagen_url' => '/marca/logo.png',
            'criterio_logro' => 'Aprobar un examen del curso con la nota mínima establecida.',
        ];
    }
}
