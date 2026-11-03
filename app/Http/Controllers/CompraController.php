<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Pago;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CompraController extends Controller
{
    public function crear(Curso $curso): View|RedirectResponse
    {
        abort_unless($curso->publicado, 404);
        if (! $curso->sePuedeComprar()) {
            return redirect()->route('cursos.ver', $curso)
                ->withErrors(['curso' => 'Este curso no tiene precio publicado. Consúltalo por WhatsApp.']);
        }

        $yaMatriculado = Matricula::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->exists();

        $pendiente = Pago::query()
            ->where('user_id', auth()->id())
            ->where('curso_id', $curso->id)
            ->where('estado', 'pendiente')
            ->latest()
            ->first();

        return view('compra.crear', [
            'curso' => $curso,
            'yaMatriculado' => $yaMatriculado,
            'pendiente' => $pendiente,
        ]);
    }

    public function guardar(Request $request, Curso $curso): RedirectResponse
    {
        abort_unless($curso->publicado && $curso->sePuedeComprar(), 403);

        $yaMatriculado = Matricula::query()
            ->where('user_id', $request->user()->id)
            ->where('curso_id', $curso->id)
            ->where('estado', 'activa')
            ->exists();
        if ($yaMatriculado) {
            return redirect()->route('aula.curso', $curso);
        }

        $datos = $request->validate([
            'comprobante' => [
                'required', 'file', 'max:5120',
                'mimes:jpg,jpeg,png,webp,pdf',
                'mimetypes:image/jpeg,image/png,image/webp,application/pdf',
            ],
            'referencia' => ['nullable', 'string', 'max:80'],
        ], [
            'comprobante.required' => 'Adjunta el comprobante de pago.',
            'comprobante.mimes' => 'El comprobante debe ser JPG, PNG, WEBP o PDF.',
            'comprobante.mimetypes' => 'El archivo no es una imagen o un PDF válido.',
            'comprobante.max' => 'El comprobante no puede superar 5 MB.',
        ]);

        $archivo = $request->file('comprobante');
        $ruta = $archivo->store('', 'comprobantes');

        Pago::query()->create([
            'user_id' => $request->user()->id,
            'curso_id' => $curso->id,
            'monto' => $curso->precio,
            'moneda' => $curso->moneda ?: 'PEN',
            'estado' => 'pendiente',
            'comprobante_path' => $ruta,
            'comprobante_nombre' => $archivo->getClientOriginalName(),
            'comprobante_mime' => $archivo->getMimeType() ?: $archivo->getClientMimeType(),
            'referencia' => $datos['referencia'] ?? null,
        ]);

        return redirect()->route('aula.pagos')->with('estado', 'Recibimos tu comprobante. La matrícula se activa cuando administración lo apruebe.');
    }

    public function descargar(Pago $pago): StreamedResponse
    {
        $usuario = request()->user();
        abort_unless($usuario && ($usuario->esAdmin() || $usuario->id === $pago->user_id), 403);
        abort_unless(Storage::disk('comprobantes')->exists($pago->comprobante_path), 404);

        return Storage::disk('comprobantes')->download($pago->comprobante_path, $pago->comprobante_nombre);
    }
}
