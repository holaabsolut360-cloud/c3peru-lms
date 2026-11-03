<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MarcaSeeder::class,
            CursosSeeder::class,
            PaginasSeeder::class,
            ReferenciasSeeder::class,
            NosotrosSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
