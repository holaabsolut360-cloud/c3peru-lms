<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\CalificacionesExport;
use App\Models\Curso;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CalificacionController extends Controller
{
    public function exportar(Curso $curso): BinaryFileResponse
    {
        $nombreArchivo = 'calificaciones-'.$curso->slug.'-'.now()->format('Y-m-d').'.xlsx';

        return Excel::download(new CalificacionesExport($curso->id), $nombreArchivo);
    }
}
