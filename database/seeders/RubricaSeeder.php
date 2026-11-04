<?php

namespace Database\Seeders;

use App\Models\Tarea;
use Illuminate\Database\Seeder;

class RubricaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarea = Tarea::query()->first();
        if (! $tarea || $tarea->rubricas()->exists()) {
            return;
        }

        $tarea->rubricas()->create([
            'criterio' => 'Calidad técnica',
            'puntaje_maximo' => 5,
            'orden' => 1,
        ]);
    }
}
