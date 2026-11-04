<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Curso>
 */
class CursoFactory extends Factory
{
    protected $model = Curso::class;

    public function definition(): array
    {
        $titulo = fake()->unique()->sentence(3);

        return [
            'categoria_id' => Categoria::factory(),
            'docente_id' => null,
            'titulo' => rtrim($titulo, '.'),
            'slug' => Str::slug($titulo).'-'.fake()->unique()->numerify('###'),
            'nivel' => 'NDT Level I',
            'duracion' => '24 hrs',
            'modalidad' => 'Semipresencial',
            'fechas' => 'Mar–Dic',
            'certificacion' => 'Certificación SNT-TC-1A',
            'precio' => 970,
            'moneda' => 'PEN',
            'incluye_igv' => true,
            'descripcion' => 'Curso de capacitación profesional.',
            'publicado' => true,
            'destacado' => false,
            'orden' => 1,
        ];
    }

    public function consultar(): static
    {
        return $this->state(fn () => [
            'precio' => null,
            'modalidad' => 'Consultar',
        ]);
    }
}
