<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: {{ $marca->color_fondo }}; margin: 0; }
        .marco { border: 8px solid {{ $marca->color_primario }}; padding: 28px; min-height: 480px; }
        .barra { background: {{ $marca->color_secundario }}; color: white; padding: 10px 16px; }
        h1 { color: {{ $marca->color_primario }}; font-size: 28px; margin: 12px 0; }
        .acento { color: {{ $marca->color_acento }}; }
        table { width: 100%; }
        .codigo { font-size: 16px; letter-spacing: 1px; color: {{ $marca->color_secundario }}; }
    </style>
</head>
<body>
    <div class="marco">
        <table>
            <tr>
                <td><img src="{{ public_path(ltrim($marca->logo, '/')) }}" alt="{{ $marca->nombre_comercial }}" style="height: 64px;"></td>
                <td style="text-align: right;">
                    <div class="acento">{{ $marca->lema }}</div>
                    <div>{{ $marca->razon_social }}</div>
                    <div>RUC {{ $marca->ruc }}</div>
                </td>
            </tr>
        </table>
        <div class="barra">Certificado de capacitación</div>
        <h1>{{ $certificado->curso_titulo }}</h1>
        <p>Se deja constancia de que <strong>{{ $certificado->nombre }}</strong> completó el registro de certificación de este curso.</p>
        <p>Documento {{ strtoupper($certificado->documento_tipo) }} registrado para verificación. Horas: {{ $certificado->horas ?: 'según programa' }}.</p>
        <p>Fecha de emisión: {{ $certificado->emitido_en->timezone(config('app.timezone'))->format('d/m/Y') }} · Estado: {{ $certificado->estadoLegible() }}</p>
        <p class="codigo">Código {{ $certificado->codigo }}</p>
        <table>
            <tr>
                <td>
                    <img src="{{ $qr }}" alt="Código QR" style="height: 110px;">
                    <div style="font-size: 11px;">El QR contiene solo el código. La consulta pública es por formulario.</div>
                </td>
                <td style="text-align: right; font-size: 12px;">
                    {{ $marca->direccion }}<br>
                    {{ $marca->correo }} · {{ $marca->telefono }}<br>
                    {{ $marca->sitio }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
