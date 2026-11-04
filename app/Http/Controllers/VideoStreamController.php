<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Video;
use App\Services\SemaforoAlumnoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VideoStreamController extends Controller
{
    public function __invoke(Request $request, Video $video, SemaforoAlumnoService $semaforo): BinaryFileResponse
    {
        abort_unless($request->hasValidSignature(), 403);
        abort_unless((int) $request->query('user_id') === (int) $request->user()->getAuthIdentifier(), 403);
        abort_unless($video->protegido, 404);

        $video->loadMissing('leccion');
        $matriculado = Matricula::query()
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('curso_id', $video->leccion->curso_id)
            ->where('estado', 'activa')
            ->exists();
        abort_unless($matriculado, 403);

        $disco = Storage::disk('local');
        abort_unless($disco->exists($video->archivo_path), 404);
        abort_unless(in_array($video->mime_type, ['video/mp4', 'video/webm'], true), 404);
        $semaforo->registrarVideoVisto(
            (int) $request->user()->getAuthIdentifier(),
            (int) $video->leccion->curso_id,
            (int) $video->leccion_id,
        );

        return response()->file($disco->path($video->archivo_path), [
            'Content-Type' => $video->mime_type,
            'Content-Disposition' => 'inline',
            'Cache-Control' => 'private, no-store, no-cache, must-revalidate, max-age=0',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
