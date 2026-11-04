<?php

namespace Database\Seeders;

use App\Models\Pagina;
use Illuminate\Database\Seeder;

class PaginasSeeder extends Seeder
{
    public function run(): void
    {
        foreach (require database_path('seeders/data/paginas.php') as $pagina) {
            Pagina::query()->updateOrCreate(
                ['slug' => $pagina['slug']],
                [
                    'titulo' => $pagina['titulo'],
                    'cuerpo' => $pagina['cuerpo'],
                    'grupo' => $pagina['grupo'],
                    'orden' => $pagina['orden'],
                    'en_sitemap' => true,
                ],
            );
        }
    }
}
