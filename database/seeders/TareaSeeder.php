<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Tarea;
use Illuminate\Database\Seeder;

class TareaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curso = Curso::query()->whereNotNull('docente_id')->first();
        if (! $curso) {
            return;
        }

        $tarea = Tarea::query()->firstOrCreate(
            ['curso_id' => $curso->id, 'titulo' => 'Actividad práctica de ejemplo'],
            [
                'docente_id' => $curso->docente_id,
                'instrucciones' => 'Actividad demostrativa para revisar el flujo de entrega y evaluación.',
                'fecha_entrega' => now()->addDays(14),
                'publicada' => true,
            ],
        );

        if ($tarea->rubricas()->exists()) {
            return;
        }

        $tarea->rubricas()->createMany([
            ['criterio' => 'Procedimiento', 'puntaje_maximo' => 5, 'orden' => 1],
            ['criterio' => 'Seguridad', 'puntaje_maximo' => 5, 'orden' => 2],
        ]);
    }
}
