<?php

namespace Database\Factories;

use App\Models\Curso;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pago>
 */
class PagoFactory extends Factory
{
    protected $model = Pago::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'curso_id' => Curso::factory(),
            'monto' => 970,
            'moneda' => 'PEN',
            'estado' => 'pendiente',
            'comprobante_path' => 'comprobante-prueba.pdf',
            'comprobante_nombre' => 'voucher.pdf',
            'comprobante_mime' => 'application/pdf',
            'referencia' => null,
        ];
    }
}
