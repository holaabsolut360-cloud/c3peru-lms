<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\MatriculaMasivaImport;
use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class MatriculaMasivaController extends Controller
{
    public function create(Curso $curso): View
    {
        return view('admin.matriculas.masiva', compact('curso'));
    }

    public function descargarPlantilla(Curso $curso): StreamedResponse
    {
        return response()->streamDownload(function (): void {
            $salida = fopen('php://output', 'w');
            if ($salida === false) {
                return;
            }

            fwrite($salida, "\xEF\xBB\xBF");
            fputcsv($salida, ['dni', 'nombres', 'apellidos', 'email', 'telefono', 'empresa'], ',', '"', '');
            fputcsv($salida, ['74521893', 'Juan Carlos', 'Pérez García', 'juan@empresa.com', '987654321', 'Minera XYZ S.A.C.'], ',', '"', '');
            fclose($salida);
        }, 'plantilla-matricula-'.$curso->slug.'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function store(Request $request, Curso $curso): RedirectResponse
    {
        $datos = $request->validate([
            'archivo' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ], [
            'archivo.required' => 'Selecciona el archivo con la lista de alumnos.',
            'archivo.mimes' => 'El archivo debe tener formato XLSX, XLS o CSV.',
            'archivo.max' => 'El archivo no puede superar los 10 MB.',
        ]);

        $importacion = new MatriculaMasivaImport($curso);

        try {
            Excel::import($importacion, $datos['archivo']);
        } catch (Throwable $excepcion) {
            report($excepcion);

            return back()->withInput()->withErrors([
                'archivo' => 'No se pudo leer el archivo. Verifica que incluya una fila de encabezados y vuelve a intentarlo.',
            ]);
        }

        return redirect()
            ->route('admin.cursos.matriculas.masiva', $curso)
            ->with('matriculaMasiva', $importacion->resumen());
    }
}
