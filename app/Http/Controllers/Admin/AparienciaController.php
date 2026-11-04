<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionApariencia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AparienciaController extends Controller
{
    public function editar(): View
    {
        $marca = ConfiguracionApariencia::query()->first() ?? ConfiguracionApariencia::desdeConfiguracion();

        return view('admin.apariencia', ['registro' => $marca]);
    }

    public function actualizar(Request $request): RedirectResponse
    {
        $colores = ['color_primario', 'color_secundario', 'color_acento', 'color_fondo', 'color_fondo_alt', 'color_whatsapp'];
        $reglas = [
            'nombre_comercial' => ['required', 'string', 'max:120'],
            'lema' => ['required', 'string', 'max:180'],
            'eslogan' => ['required', 'string', 'max:180'],
            'razon_social' => ['required', 'string', 'max:180'],
            'ruc' => ['required', 'digits:11'],
            'direccion' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:40'],
            'whatsapp' => ['required', 'regex:/^[0-9]{8,15}$/'],
            'correo' => ['required', 'email', 'max:160'],
            'logo' => ['required', 'string', 'max:255'],
            'favicon' => ['required', 'string', 'max:255'],
            'apple_touch' => ['required', 'string', 'max:255'],
            'fuente_titulos' => ['required', 'regex:/^[A-Za-z][A-Za-z0-9 \-]{0,40}$/'],
            'fuente_ui' => ['required', 'regex:/^[A-Za-z][A-Za-z0-9 \-]{0,40}$/'],
            'fuente_texto' => ['required', 'regex:/^[A-Za-z][A-Za-z0-9 \-]{0,40}$/'],
            'facebook' => ['required', 'url', 'max:255'],
            'instagram' => ['required', 'url', 'max:255'],
            'youtube' => ['required', 'url', 'max:255'],
            'tiktok' => ['required', 'url', 'max:255'],
            'linkedin' => ['required', 'url', 'max:255'],
            'texto_footer' => ['required', 'string', 'max:255'],
            'copyright' => ['required', 'string', 'max:255'],
            'meta_descripcion' => ['required', 'string', 'max:300'],
            'prefijo_certificado' => ['required', 'regex:/^[A-Za-z0-9]{2,12}$/'],
            'gerente_general' => ['required', 'string', 'max:160'],
            'sitio' => ['required', 'url', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:255'],
            'video_archivo' => ['nullable', 'file', 'max:51200', 'mimetypes:video/mp4,video/webm'],
        ];
        foreach ($colores as $color) {
            $reglas[$color] = ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'];
        }

        $datos = $request->validate($reglas, [
            'video_url.url' => 'La URL del video debe ser un enlace completo de YouTube o de un archivo.',
            'video_archivo.mimetypes' => 'El video debe ser MP4 o WEBM.',
        ]);
        $datos['video_url'] = $request->input('video_url') ?: null;
        unset($datos['video_archivo']);
        if ($request->hasFile('video_archivo')) {
            $datos['video_archivo'] = '/storage/'.$request->file('video_archivo')->store('videos', 'public');
        }
        $marca = ConfiguracionApariencia::query()->first() ?? new ConfiguracionApariencia;
        $marca->fill($datos)->save();

        return back()->with('estado', 'La configuración de marca se guardó. PDF, correos y el sitio público la tomarán de aquí.');
    }
}
