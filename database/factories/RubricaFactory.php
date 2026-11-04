<?php

namespace Database\Factories;

use App\Models\Rubrica;
use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rubrica>
 */
class RubricaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tarea_id' => Tarea::factory(),
            'criterio' => fake()->randomElement(['Precisión técnica', 'Seguridad', 'Procedimiento']),
            'puntaje_maximo' => 5,
            'descripcion_nivel_alto' => 'Cumple todos los requisitos con precisión.',
            'descripcion_nivel_medio' => 'Cumple la mayoría de los requisitos.',
            'descripcion_nivel_bajo' => 'Necesita mejorar varios aspectos.',
            'orden' => 0,
        ];
    }
}
