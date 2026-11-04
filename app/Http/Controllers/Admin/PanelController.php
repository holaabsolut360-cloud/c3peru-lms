<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pago;
use App\Models\User;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function __invoke(): View
    {
        $inicioMes = now()->startOfMonth();

        $metrics = [
            'alumnos' => User::query()->where('role', 'alumno')->count(),
            'cursosPublicados' => Curso::query()->where('publicado', true)->count(),
            'ingresoMes' => (float) Pago::query()
                ->where('estado', 'aprobado')
                ->whereBetween('created_at', [$inicioMes, $inicioMes->copy()->endOfMonth()])
                ->sum('monto'),
            'certificados' => Certificado::query()->count(),
        ];

        $ingresosMensuales = collect(range(5, 0))->map(function (int $mesesAtras): array {
            $mes = now()->locale('es')->startOfMonth()->subMonths($mesesAtras);

            return [
                'etiqueta' => $mes->translatedFormat('M'),
                'total' => (float) Pago::query()
                    ->where('estado', 'aprobado')
                    ->whereBetween('created_at', [$mes, $mes->copy()->endOfMonth()])
                    ->sum('monto'),
            ];
        });

        $cursosPopulares = Curso::query()
            ->select(['id', 'titulo'])
            ->withCount('matriculas')
            ->orderByDesc('matriculas_count')
            ->limit(5)
            ->get();

        $inscripcionesSemanales = collect(range(3, 0))->map(function (int $semanasAtras): array {
            $inicioSemana = now()->startOfWeek()->subWeeks($semanasAtras);

            return [
                'etiqueta' => $inicioSemana->format('d/m'),
                'total' => Matricula::query()
                    ->whereBetween('created_at', [$inicioSemana, $inicioSemana->copy()->endOfWeek()])
                    ->count(),
            ];
        });

        $pagosPendientes = Pago::query()
            ->with(['usuario:id,name', 'curso:id,titulo'])
            ->where('estado', 'pendiente')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.inicio', [
            'metrics' => $metrics,
            'ingresosMensuales' => $ingresosMensuales,
            'cursosPopulares' => $cursosPopulares,
            'inscripcionesSemanales' => $inscripcionesSemanales,
            'pagosPendientes' => $pagosPendientes,
        ]);
    }
}
