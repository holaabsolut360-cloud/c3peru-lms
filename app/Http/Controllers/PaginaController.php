<?php

namespace App\Http\Controllers;

use App\Models\Pagina;
use Illuminate\View\View;

class PaginaController extends Controller
{
    public function ver(Pagina $pagina): View
    {
        return view('paginas.ver', ['pagina' => $pagina]);
    }
}
