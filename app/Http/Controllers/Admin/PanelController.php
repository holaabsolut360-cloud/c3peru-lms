<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use App\Models\Pago;
use App\Models\Reclamacion;
use App\Models\User;
use Illuminate\View\View;

class PanelController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.inicio', [
            'pagosPendientes' => Pago::query()->where('estado', 'pendiente')->count(),
            'alumnos' => User::query()->where('role', 'alumno')->count(),
            'certificados' => Certificado::query()->count(),
            'reclamos' => Reclamacion::query()->count(),
        ]);
    }
}
