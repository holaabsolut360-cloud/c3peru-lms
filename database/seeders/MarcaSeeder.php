<?php

namespace Database\Seeders;

use App\Models\ConfiguracionApariencia;
use Illuminate\Database\Seeder;

class MarcaSeeder extends Seeder
{
    public function run(): void
    {
        ConfiguracionApariencia::query()->updateOrCreate(['id' => 1], config('marca'));
        ConfiguracionApariencia::olvidar();
    }
}
