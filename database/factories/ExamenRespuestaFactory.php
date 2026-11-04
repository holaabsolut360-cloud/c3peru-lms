<?php

namespace Database\Factories;

use App\Models\ExamenIntento;
use App\Models\Pregunta;
use App\Models\ExamenRespuesta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExamenRespuesta>
use App\Models\ExamenIntento;
use App\Models\Pregunta;
 */
class ExamenRespuestaFactory extends Factory
{
    /**
            'examen_intento_id' => ExamenIntento::factory(),
            'pregunta_id' => Pregunta::factory(),
            'respuesta_seleccionada' => 'A',
            'es_correcta' => true,
            'puntaje_obtenido' => 1,
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'examen_intento_id' => ExamenIntento::factory(),
            'pregunta_id' => Pregunta::factory(),
            'respuesta_seleccionada' => 'A',
            'es_correcta' => true,
            'puntaje_obtenido' => 1,
        ];
    }
}
