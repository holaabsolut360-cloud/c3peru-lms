<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\LeccionMaterial;
use App\Models\Matricula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipStream\ZipStream;

class RecursosController extends Controller
{
    public function index(Curso $curso): View
    {
        $this->autorizarAcceso($curso);
        $lecciones = $curso->lecciones()
            ->with('materiales')
            ->orderBy('orden')
            ->get();

        return view('aula.recursos', [
            'curso' => $curso,
            'lecciones' => $lecciones,
            'cantidadMateriales' => $lecciones->sum(fn ($leccion): int => $leccion->materiales->count()),
        ]);
    }

    public function descargarZip(Curso $curso): StreamedResponse|RedirectResponse
    {
        $this->autorizarAcceso($curso);

        $materiales = LeccionMaterial::query()
            ->whereHas('leccion', fn ($consulta) => $consulta->where('curso_id', $curso->id))
            ->with('leccion')
            ->orderBy('leccion_id')
            ->orderBy('nombre_original')
            ->get()
            ->filter(fn (LeccionMaterial $material): bool => Storage::disk('local')->exists($material->archivo_path));

        if ($materiales->isEmpty()) {
            return to_route('aula.recursos.index', $curso)->with('estado', 'Este curso todavía no tiene recursos disponibles para descargar.');
        }

        $nombreArchivo = (Str::slug($curso->titulo) ?: 'curso').'-recursos.zip';

        return response()->streamDownload(function () use ($materiales): void {
            $archivoZip = new ZipStream(
                outputStream: fopen('php://output', 'w'),
                sendHttpHeaders: false,
            );

            foreach ($materiales as $material) {
                $directorioLeccion = Str::slug($material->leccion->titulo) ?: 'leccion-'.$material->leccion_id;
                $nombreOriginal = basename(str_replace('\\', '/', $material->nombre_original));
                $nombreLimpio = trim((string) preg_replace('/[^\pL\pN._ -]+/u', '-', $nombreOriginal), ' .-');
                $nombreLimpio = $nombreLimpio !== '' ? $nombreLimpio : 'material-'.$material->getKey();
                $nombreEnZip = $directorioLeccion.'/'.$material->getKey().'-'.$nombreLimpio;
                $rutaPrivada = Storage::disk('local')->path($material->archivo_path);

                if (is_readable($rutaPrivada)) {
                    $archivoZip->addFileFromPath(
                        fileName: $nombreEnZip,
                        path: $rutaPrivada,
                    );
                }
            }

            $archivoZip->finish();
        }, $nombreArchivo, [
            'Content-Type' => 'application/zip',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    private function autorizarAcceso(Curso $curso): void
    {
        abort_unless(auth()->user()->role === 'alumno', 403);
        abort_unless(Matricula::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->exists(), 403);
    }
}
