<?php

namespace Database\Seeders;

use App\Models\RubricaCalificacion;
use App\Models\TareaEntrega;
use Illuminate\Database\Seeder;

class RubricaCalificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entrega = TareaEntrega::query()->with(['tarea.rubricas', 'tarea.curso'])->first();
        if (! $entrega || $entrega->tarea->rubricas->isEmpty() || ! $entrega->tarea->docente_id) {
            return;
        }

        foreach ($entrega->tarea->rubricas as $rubrica) {
            RubricaCalificacion::query()->firstOrCreate(
                ['rubrica_id' => $rubrica->id, 'tarea_entrega_id' => $entrega->id],
                [
                    'docente_id' => $entrega->tarea->docente_id,
                    'puntaje_obtenido' => min(4, $rubrica->puntaje_maximo),
                    'comentario' => 'Comentario de calificación de ejemplo.',
                ],
            );
        }
    }
}
