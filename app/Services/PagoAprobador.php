<?php

namespace App\Services;

use App\Models\Matricula;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PagoAprobador
{
    public function aprobar(Pago $pago, User $admin): void
    {
        if (! $admin->esAdmin()) {
            throw new RuntimeException('Solo un administrador puede aprobar pagos.');
        }

        if ($pago->estado !== 'pendiente') {
            throw new RuntimeException('Este pago ya fue revisado.');
        }

        DB::transaction(function () use ($pago, $admin): void {
            $pago->update([
                'estado' => 'aprobado',
                'aprobado_por' => $admin->id,
                'aprobado_en' => now(),
                'motivo' => null,
            ]);

            Matricula::query()->firstOrCreate(
                ['user_id' => $pago->user_id, 'curso_id' => $pago->curso_id],
                ['pago_id' => $pago->id, 'estado' => 'activa'],
            );
        });
    }

    public function rechazar(Pago $pago, User $admin, string $motivo): void
    {
        if (! $admin->esAdmin()) {
            throw new RuntimeException('Solo un administrador puede rechazar pagos.');
        }

        if ($pago->estado !== 'pendiente') {
            throw new RuntimeException('Este pago ya fue revisado.');
        }

        $pago->update([
            'estado' => 'rechazado',
            'aprobado_por' => $admin->id,
            'aprobado_en' => now(),
            'motivo' => $motivo,
        ]);
    }
}
