<?php

namespace App\Http\Controllers;

use App\Models\ActividadAlumno;
use App\Models\Matricula;
use App\Models\Tarea;
use App\Models\TareaEntrega;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TareaEntregaController extends Controller
{
    public function store(Request $request, Tarea $tarea): RedirectResponse
    {
        abort_unless($tarea->publicada, 404);
        abort_if($tarea->fecha_entrega?->isPast(), 403, 'El plazo de entrega ya terminó.');
        abort_unless($request->user()->role === 'alumno', 403);
        abort_unless(Matricula::query()
            ->where('user_id', $request->user()->id)
            ->where('curso_id', $tarea->curso_id)
            ->where('estado', 'activa')
            ->exists(), 403);

        $datos = $request->validate([
            'archivo' => ['nullable', 'file', 'max:20480', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,mp4', 'extensions:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,mp4'],
            'comentario' => ['nullable', 'string', 'max:4000'],
        ]);
        $comentario = trim((string) ($datos['comentario'] ?? ''));
        if (! $request->hasFile('archivo') && $comentario === '') {
            return back()->withErrors(['archivo' => 'Adjunta un archivo o escribe una respuesta.'])->withInput();
        }

        $archivo = $request->file('archivo');
        $rutaArchivo = $archivo?->store('tareas/'.$tarea->id.'/'.$request->user()->id, 'local');
        $entrega = DB::transaction(fn (): TareaEntrega => TareaEntrega::query()->create([
            'tarea_id' => $tarea->id,
            'user_id' => $request->user()->id,
            'archivo_path' => $rutaArchivo,
            'archivo_nombre' => $archivo?->getClientOriginalName(),
            'archivo_mime_type' => $archivo?->getMimeType(),
            'comentario' => $comentario !== '' ? $comentario : null,
            'entregado_en' => now(),
        ]));

        ActividadAlumno::query()->create([
            'user_id' => $request->user()->id,
            'curso_id' => $tarea->curso_id,
            'tipo' => ActividadAlumno::TIPO_TAREA_ENVIADA,
        ]);

        return redirect()->route('aula.curso', $tarea->curso)
            ->with('estado', 'La tarea fue entregada correctamente.')
            ->with('entrega_id', $entrega->id);
    }

    public function descargar(Request $request, TareaEntrega $entrega): StreamedResponse
    {
        $entrega->loadMissing('tarea.curso');
        if ($request->user()->role === 'docente') {
            abort_unless((int) $entrega->tarea->curso->docente_id === (int) $request->user()->id, 403);
        } else {
            abort_unless($request->user()->role === 'alumno' && (int) $entrega->user_id === (int) $request->user()->id, 403);
        }
        abort_unless($entrega->archivo_path && Storage::disk('local')->exists($entrega->archivo_path), 404);

        return Storage::disk('local')->download(
            $entrega->archivo_path,
            $entrega->archivo_nombre ?? basename($entrega->archivo_path),
        );
    }
}
