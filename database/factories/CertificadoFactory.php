<?php

namespace Database\Factories;

use App\Models\Certificado;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certificado>
 */
class CertificadoFactory extends Factory
{
    protected $model = Certificado::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'curso_id' => Curso::factory(),
            'matricula_id' => function (array $atributos) {
                return Matricula::query()->create([
                    'user_id' => $atributos['user_id'],
                    'curso_id' => $atributos['curso_id'],
                    'estado' => 'activa',
                ])->id;
            },
            'codigo' => 'C3-'.now()->year.'-'.strtoupper(fake()->unique()->bothify('?#?#?#')),
            'documento_tipo' => 'dni',
            'documento_numero' => fn () => (string) fake()->unique()->numerify('########'),
            'nombre' => 'Ana Castillo',
            'curso_titulo' => 'Inspección Visual',
            'horas' => '24 hrs',
            'emitido_en' => now(),
            'estado' => 'vigente',
        ];
    }
}
