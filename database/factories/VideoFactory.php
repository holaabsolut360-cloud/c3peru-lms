<?php

namespace Database\Factories;

use App\Models\Leccion;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
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
            'titulo' => fake()->sentence(3),
            'archivo_path' => 'videos/'.fake()->uuid().'.mp4',
            'mime_type' => 'video/mp4',
            'duracion_segundos' => 120,
            'protegido' => true,
        ];
    }
}
