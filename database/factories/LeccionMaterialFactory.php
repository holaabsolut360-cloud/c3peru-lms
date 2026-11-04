<?php

namespace Database\Factories;

use App\Models\Leccion;
use App\Models\LeccionMaterial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeccionMaterial>
 */
class LeccionMaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'leccion_id' => Leccion::factory(),
            'nombre_original' => fake()->word().'.pdf',
            'archivo_path' => 'materiales/'.fake()->uuid().'.pdf',
            'mime_type' => 'application/pdf',
            'tamano_bytes' => fake()->numberBetween(1024, 1048576),
        ];
    }
}
