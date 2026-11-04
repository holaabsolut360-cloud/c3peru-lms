<?php

namespace App\Services;

use App\Models\Matricula;
use App\Models\Pago;
use App\Models\User;
use App\Notifications\NotificacionCentro;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

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

        try {
            $pago->loadMissing(['curso', 'usuario']);
            $pago->usuario->notify(new NotificacionCentro(
                'Pago aprobado',
                'Tu matrícula en «'.$pago->curso->titulo.'» ya está activa.',
                route('aula.curso', $pago->curso, false),
                'pago',
            ));
        } catch (Throwable $exception) {
            report($exception);
            Log::warning('No se pudo guardar la notificación de aprobación de pago.', [
                'pago_id' => $pago->id,
                'user_id' => $pago->user_id,
            ]);
        }
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
