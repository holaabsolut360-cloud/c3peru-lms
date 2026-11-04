<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Leccion;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VideoController extends Controller
{
    public function index(Curso $curso): View
    {
        return view('admin.videos.index', [
            'curso' => $curso->load(['lecciones' => function ($consulta): void {
                $consulta->with('videos')->orderBy('orden');
            }]),
            'lecciones' => $curso->lecciones()->orderBy('orden')->get(),
        ]);
    }

    public function store(Request $request, Curso $curso): RedirectResponse
    {
        $datos = $request->validate([
            'leccion_id' => [
                'required',
                'integer',
                Rule::exists('lecciones', 'id')->where('curso_id', $curso->id),
            ],
            'titulo' => ['required', 'string', 'max:180'],
            'video' => ['required', 'file', 'max:204800', 'mimes:mp4,webm', 'mimetypes:video/mp4,video/webm'],
        ], [
            'video.mimes' => 'El video debe ser MP4 o WEBM.',
            'video.mimetypes' => 'El archivo no es un video MP4 o WEBM válido.',
            'video.max' => 'El video no puede superar 200 MB.',
        ]);

        $archivo = $request->file('video');
        $ruta = $archivo->store('videos', 'local');

        Video::query()->create([
            'leccion_id' => $datos['leccion_id'],
            'titulo' => $datos['titulo'],
            'archivo_path' => $ruta,
            'mime_type' => $archivo->getMimeType() ?: $archivo->getClientMimeType(),
            'protegido' => true,
        ]);

        return back()->with('estado', 'El video quedó guardado en almacenamiento privado.');
    }

    public function destroy(Curso $curso, Video $video): RedirectResponse
    {
        $video->load('leccion');
        abort_unless((int) $video->leccion->curso_id === (int) $curso->id, 404);

        Storage::disk('local')->delete($video->archivo_path);
        $video->delete();

        return back()->with('estado', 'Video eliminado.');
    }
}
