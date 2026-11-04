<?php

namespace Database\Factories;

use App\Models\Examen;
use App\Models\Pregunta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pregunta>
use App\Models\Examen;
 */
class PreguntaFactory extends Factory
{
    /**
            'examen_id' => Examen::factory(),
            'leccion_id' => null,
            'enunciado' => fake()->sentence(). '?',
            'opciones' => [
                'A' => 'Opción correcta',
                'B' => 'Opción alternativa',
                'C' => 'Otra opción',
                'D' => 'Última opción',
            ],
            'respuesta_correcta' => 'A',
            'puntaje' => 1,
            'explicacion' => null,
            'dificultad' => 'medio',
            'orden' => 1,
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'examen_id' => Examen::factory(),
            'leccion_id' => null,
            'enunciado' => fake()->sentence(). '?',
            'opciones' => [
                'A' => 'Opción correcta',
                'B' => 'Opción alternativa',
                'C' => 'Otra opción',
                'D' => 'Última opción',
            ],
            'respuesta_correcta' => 'A',
            'puntaje' => 1,
            'explicacion' => null,
            'dificultad' => 'medio',
            'orden' => 1,
        ];
    }
}
