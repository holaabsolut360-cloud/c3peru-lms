<?php

namespace Database\Factories;

use App\Models\Tarea;
use App\Models\TareaEntrega;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TareaEntrega>
 */
class TareaEntregaFactory extends Factory
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
            'user_id' => User::factory(),
            'archivo_path' => null,
            'archivo_nombre' => null,
            'archivo_mime_type' => null,
            'comentario' => fake()->paragraph(),
            'entregado_en' => now(),
            'puntaje_total' => null,
            'comentario_docente' => null,
            'calificada_en' => null,
        ];
    }
}
