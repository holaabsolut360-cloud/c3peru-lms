<?php

namespace Database\Factories;

use App\Models\Insignia;
use App\Models\InsigniaUsuario;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<InsigniaUsuario>
 */
class InsigniaUsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'insignia_id' => Insignia::factory(),
            'user_id' => User::factory(),
            'badge_url' => fake()->url().'/badges/class',
            'assertion_url' => fake()->url().'/badges/'.Str::uuid(),
            'otorgada_en' => now(),
        ];
    }
}
