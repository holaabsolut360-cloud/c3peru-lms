<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reclamacion;
use Illuminate\View\View;

class ReclamacionAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.reclamaciones', [
            'reclamaciones' => Reclamacion::query()->latest()->get(),
        ]);
    }
}
