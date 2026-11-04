<?php

namespace Database\Factories;

use App\Models\ExamenIntento;
use App\Models\Examen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExamenIntento>
use App\Models\Examen;
use App\Models\User;
 */
class ExamenIntentoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
            'examen_id' => Examen::factory(),
            'user_id' => User::factory(),
            'iniciado_en' => $iniciadoEn,
            'expira_en' => $iniciadoEn->copy()->addMinutes(30),
            'finalizado_en' => null,
            'puntaje_total' => 0,
            'nota_final' => null,
            'porcentaje' => null,
            'aprobado' => null,
     */
    public function definition(): array
    {
        $iniciadoEn = now();

        return [
            'examen_id' => Examen::factory(),
            'user_id' => User::factory(),
            'iniciado_en' => $iniciadoEn,
            'expira_en' => $iniciadoEn->copy()->addMinutes(30),
            'finalizado_en' => null,
            'puntaje_total' => 0,
            'nota_final' => null,
            'porcentaje' => null,
            'aprobado' => null,
        ];
    }
}
