<?php

namespace App\Http\Controllers\Docente;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\LeccionMaterial;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContenidoController extends Controller
{
    public function index(Curso $curso): View
    {
        $this->autorizarCurso($curso);

        return view('docente.contenido.index', [
            'curso' => $curso->load(['lecciones' => function ($consulta): void {
                $consulta->with(['videos', 'materiales'])->orderBy('orden');
            }]),
            'lecciones' => $curso->lecciones()->orderBy('orden')->get(),
        ]);
    }

    public function store(Request $request, Curso $curso): RedirectResponse
    {
        $this->autorizarCurso($curso);

        $datos = $request->validate([
            'leccion_id' => [
                'required',
                'integer',
                Rule::exists('lecciones', 'id')->where('curso_id', $curso->id),
            ],
            'titulo' => ['required', 'string', 'max:180'],
            'video' => ['required', 'file', 'max:204800', 'mimes:mp4,webm', 'mimetypes:video/mp4,video/webm'],
            'materiales' => ['sometimes', 'array', 'max:10'],
            'materiales.*' => [
                'file',
                'max:51200',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip',
                'extensions:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip',
            ],
        ], [
            'video.mimes' => 'El video debe ser MP4 o WEBM.',
            'video.mimetypes' => 'El archivo no es un video MP4 o WEBM válido.',
            'video.max' => 'El video no puede superar 200 MB.',
            'materiales.max' => 'Puedes adjuntar hasta 10 archivos complementarios.',
            'materiales.*.max' => 'Cada material complementario debe pesar como máximo 50 MB.',
        ]);

        $archivo = $request->file('video');
        $ruta = $archivo->store('videos', 'local');

        $leccion = $curso->lecciones()->findOrFail($datos['leccion_id']);
        $leccion->videos()->create([
            'titulo' => $datos['titulo'],
            'archivo_path' => $ruta,
            'mime_type' => $archivo->getMimeType() ?: $archivo->getClientMimeType(),
            'protegido' => true,
        ]);

        foreach ($request->file('materiales', []) as $material) {
            LeccionMaterial::query()->create([
                'leccion_id' => $leccion->id,
                'nombre_original' => mb_substr($material->getClientOriginalName(), 0, 255),
                'archivo_path' => $material->store('materiales/lecciones/'.$leccion->id, 'local'),
                'mime_type' => $material->getMimeType() ?: $material->getClientMimeType(),
                'tamano_bytes' => $material->getSize(),
            ]);
        }

        return back()->with('estado', 'El video quedó guardado en almacenamiento privado.');
    }

    public function destroy(Curso $curso, Video $video): RedirectResponse
    {
        $this->autorizarCurso($curso);
        $video->load('leccion');

        abort_unless((int) $video->leccion->curso_id === (int) $curso->id, 404);

        Storage::disk('local')->delete($video->archivo_path);
        $video->delete();

        return back()->with('estado', 'Video eliminado.');
    }

    public function destroyMaterial(Curso $curso, LeccionMaterial $material): RedirectResponse
    {
        $this->autorizarCurso($curso);
        $material->load('leccion');

        abort_unless((int) $material->leccion->curso_id === (int) $curso->id, 404);

        Storage::disk('local')->delete($material->archivo_path);
        $material->delete();

        return back()->with('estado', 'Material complementario eliminado.');
    }

    private function autorizarCurso(Curso $curso): void
    {
        abort_unless((int) $curso->docente_id === (int) auth()->id(), 403);
    }
}
