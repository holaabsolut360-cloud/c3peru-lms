<?php

namespace Database\Factories;

use App\Models\ClaseEnVivo;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClaseEnVivo>
 */
class ClaseEnVivoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaInicio = now()->addDay();

        return [
            'curso_id' => Curso::factory(),
            'leccion_id' => null,
            'docente_id' => User::factory()->docente(),
            'titulo' => fake()->sentence(4),
            'descripcion' => fake()->sentence(),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaInicio->copy()->addHour(),
            'teams_join_url' => null,
            'estado' => 'programada',
        ];
    }
}
