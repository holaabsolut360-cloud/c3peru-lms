<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificado;
use App\Models\Matricula;
use App\Services\CertificadoEmisor;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use RuntimeException;

class CertificadoController extends Controller
{
    public function index(): View
    {
        return view('admin.certificados', [
            'matriculas' => Matricula::query()->with(['usuario', 'curso', 'certificado'])->latest()->get(),
            'certificados' => Certificado::query()->latest('emitido_en')->get(),
        ]);
    }

    public function emitir(Matricula $matricula, CertificadoEmisor $emisor): RedirectResponse
    {
        try {
            $certificado = $emisor->emitir($matricula);
        } catch (RuntimeException $excepcion) {
            return back()->withErrors(['certificado' => $excepcion->getMessage()]);
        }

        return back()->with('estado', 'Certificado '.$certificado->codigo.' emitido.');
    }

    public function revocar(Certificado $certificado): RedirectResponse
    {
        $certificado->update(['estado' => 'revocado']);

        return back()->with('estado', 'Certificado revocado. La verificación mostrará ese estado.');
    }
}
