<?php

namespace Database\Seeders;

use App\Models\Matricula;
use App\Models\Tarea;
use App\Models\TareaEntrega;
use Illuminate\Database\Seeder;

class TareaEntregaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tarea = Tarea::query()->first();
        if (! $tarea) {
            return;
        }

        $matricula = Matricula::query()
            ->where('curso_id', $tarea->curso_id)
            ->where('estado', 'activa')
            ->first();
        if (! $matricula) {
            return;
        }

        TareaEntrega::query()->firstOrCreate(
            ['tarea_id' => $tarea->id, 'user_id' => $matricula->user_id],
            [
                'comentario' => 'Entrega de ejemplo para revisar la rúbrica.',
                'entregado_en' => now(),
            ],
        );
    }
}
