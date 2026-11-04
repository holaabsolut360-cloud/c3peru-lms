<?php

namespace Database\Factories;

use App\Models\Rubrica;
use App\Models\RubricaCalificacion;
use App\Models\TareaEntrega;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RubricaCalificacion>
 */
class RubricaCalificacionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rubrica_id' => Rubrica::factory(),
            'tarea_entrega_id' => TareaEntrega::factory(),
            'docente_id' => User::factory()->docente(),
            'puntaje_obtenido' => 4,
            'comentario' => fake()->sentence(),
            'comentario_audio_url' => null,
        ];
    }
}
