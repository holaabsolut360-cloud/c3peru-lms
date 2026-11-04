<?php

namespace Database\Seeders;

use App\Models\Curso;
use Illuminate\Database\Seeder;

class AnuncioCursoSeeder extends Seeder
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

        $curso->anuncios()->firstOrCreate(
            ['titulo' => 'Anuncio de ejemplo'],
            [
                'docente_id' => $curso->docente_id,
                'contenido' => 'Este es un anuncio de ejemplo para revisar el tablón del curso.',
                'enviar_email' => false,
                'enviar_whatsapp' => false,
                'publicado_en' => now(),
            ],
        );
    }
}
