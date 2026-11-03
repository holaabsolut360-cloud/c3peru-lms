<?php

namespace App\Http\Controllers;

use App\Models\Certificado;
use App\Models\ConfiguracionApariencia;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Response;

class CertificadoPdfController extends Controller
{
    public function __invoke(Certificado $certificado): Response
    {
        $usuario = request()->user();
        abort_unless($usuario && ($usuario->esAdmin() || $usuario->id === $certificado->user_id), 403);

        $marca = ConfiguracionApariencia::actual();
        $opciones = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'outputBase64' => true,
            'scale' => 5,
        ]);
        $qr = (new QRCode($opciones))->render($certificado->codigo);

        $pdf = Pdf::loadView('pdf.certificado', [
            'certificado' => $certificado,
            'marca' => $marca,
            'qr' => $qr,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('certificado-'.$certificado->codigo.'.pdf');
    }
}
