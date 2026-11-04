<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Insignia;
use Illuminate\Database\Seeder;

class InsigniasSeeder extends Seeder
{
    public function run(): void
    {
        Curso::query()->each(function (Curso $curso): void {
            Insignia::query()->firstOrCreate(
                ['curso_id' => $curso->id],
                [
                    'nombre' => 'Curso aprobado: '.$curso->titulo,
                    'descripcion' => 'Insignia digital otorgada por aprobar una evaluación de '.$curso->titulo.'.',
                    'imagen_url' => '/marca/logo.png',
                    'criterio_logro' => 'Aprobar un examen del curso con la nota mínima configurada (14/20 por defecto).',
                ],
            );
        });
    }
}
