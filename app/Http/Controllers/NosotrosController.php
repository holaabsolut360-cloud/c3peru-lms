<?php

namespace App\Http\Controllers;

use App\Support\Comunidad;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NosotrosController extends Controller
{
    public function index(): View
    {
        return view('nosotros', Comunidad::datos());
    }

    public function equipo(): RedirectResponse
    {
        return redirect()->to(route('nosotros').'#equipo');
    }
}
