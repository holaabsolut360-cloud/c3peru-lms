<?php

namespace Database\Seeders;

use App\Models\ReferenciaPublica;
use Illuminate\Database\Seeder;

class ReferenciasSeeder extends Seeder
{
    public function run(): void
    {
        $filas = [
            ['tipo' => 'testimonio', 'nombre' => 'Cliente minero POR CONFIRMAR', 'detalle' => 'Operación por confirmar', 'texto' => 'POR CONFIRMAR. Este testimonio es un ejemplo hasta que la empresa entregue la cita y autorice el nombre.', 'orden' => 1],
            ['tipo' => 'testimonio', 'nombre' => 'Supervisor de mantenimiento POR CONFIRMAR', 'detalle' => 'Cargo por confirmar', 'texto' => 'POR CONFIRMAR. Texto de muestra sobre la práctica en equipos reales. No corresponde a un cliente publicado.', 'orden' => 2],
            ['tipo' => 'aliado', 'nombre' => 'Aliado técnico POR CONFIRMAR', 'detalle' => 'Logo y convenio por confirmar', 'texto' => 'POR CONFIRMAR. Espacio reservado para un aliado de equipos o de certificación.', 'orden' => 1],
            ['tipo' => 'aliado', 'nombre' => 'Organismo de esquema POR CONFIRMAR', 'detalle' => 'Acreditación por confirmar', 'texto' => 'POR CONFIRMAR. No afirma una acreditación vigente hasta que exista el documento.', 'orden' => 2],
            ['tipo' => 'alianza', 'nombre' => 'Alianza sectorial POR CONFIRMAR', 'detalle' => 'Alcance por confirmar', 'texto' => 'POR CONFIRMAR. La alianza se publicará cuando C3 Perú confirme el acuerdo y el texto.', 'orden' => 1],
        ];

        foreach ($filas as $fila) {
            ReferenciaPublica::query()->updateOrCreate(
                ['tipo' => $fila['tipo'], 'nombre' => $fila['nombre']],
                [...$fila, 'por_confirmar' => true, 'publicado' => true],
            );
        }
    }
}
