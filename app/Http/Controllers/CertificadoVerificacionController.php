<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Support\CodigoCertificado;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CertificadoVerificacionController extends Controller
{
    public function formulario(): View
    {
        return view('certificados.verificar', [
            'resultados' => null,
            'consultado' => false,
        ]);
    }

    public function consultar(Request $request): View
    {
        $datos = $request->validate([
            'consulta' => ['required', 'string', 'max:40'],
        ], [
            'consulta.required' => 'Ingresa el DNI, el CE o el código del certificado.',
        ]);

        $consulta = trim($datos['consulta']);
        $resultados = $this->buscar($consulta);

        return view('certificados.verificar', [
            'resultados' => $resultados,
            'consultado' => true,
        ]);
    }

    /**
     * @return Collection<int, Certificado>
     */
    private function buscar(string $consulta)
    {
        $consultaNormalizada = mb_strtoupper(preg_replace('/\s+/', '', $consulta) ?? '');

        if (CodigoCertificado::pareceCodigo($consultaNormalizada)) {
            return Certificado::query()
                ->where('codigo', $consultaNormalizada)
                ->get();
        }

        $documento = preg_replace('/[^A-Za-z0-9]/', '', $consulta) ?? '';

        return Certificado::query()
            ->where('documento_numero', $documento)
            ->orderByDesc('emitido_en')
            ->get();
    }
}
