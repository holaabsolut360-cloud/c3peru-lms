@extends('layouts.panel')
@section('titulo', 'Marca')

@section('contenido')
    <h1 class="text-3xl text-marca">Marca y datos de la empresa</h1>
    <form method="post" action="{{ route('admin.apariencia.actualizar') }}" enctype="multipart/form-data" class="mt-6 grid max-w-3xl gap-3 md:grid-cols-2">
        @csrf
        @method('PUT')
        @foreach ([
            'nombre_comercial' => 'Nombre comercial',
            'lema' => 'Lema',
            'eslogan' => 'Eslogan',
            'razon_social' => 'Razón social',
            'ruc' => 'RUC',
            'direccion' => 'Dirección',
            'telefono' => 'Teléfono',
            'whatsapp' => 'WhatsApp (solo dígitos)',
            'correo' => 'Correo',
            'logo' => 'Ruta del logo',
            'favicon' => 'Ruta del favicon',
            'apple_touch' => 'Ruta apple touch',
            'color_primario' => 'Color primario',
            'color_secundario' => 'Color secundario',
            'color_acento' => 'Color acento',
            'color_fondo' => 'Fondo',
            'color_fondo_alt' => 'Fondo alterno',
            'color_whatsapp' => 'Verde solo del botón de WhatsApp',
            'fuente_titulos' => 'Fuente de títulos',
            'fuente_ui' => 'Fuente de interfaz',
            'fuente_texto' => 'Fuente de texto',
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'linkedin' => 'LinkedIn',
            'texto_footer' => 'Texto del footer',
            'copyright' => 'Copyright',
            'prefijo_certificado' => 'Prefijo de certificados',
            'gerente_general' => 'Gerente general',
            'sitio' => 'Sitio web',
        ] as $campo => $etiqueta)
            <label class="text-sm md:col-span-2">{{ $etiqueta }}
                <input name="{{ $campo }}" value="{{ old($campo, $registro->{$campo}) }}" class="mt-1 w-full rounded-xl border px-3 py-2">
            </label>
        @endforeach
        <label class="text-sm md:col-span-2">Video institucional (URL de YouTube o archivo). Si lo dejas vacío, la portada muestra solo la imagen.
            <input name="video_url" value="{{ old('video_url', $registro->video_url) }}" placeholder="https://www.youtube.com/watch?v=" class="mt-1 w-full rounded-xl border px-3 py-2">
            <input name="video_archivo" type="file" accept="video/mp4,video/webm" class="mt-2 block w-full text-sm">
        </label>
        <label class="text-sm md:col-span-2">Descripción para buscadores
            <textarea name="meta_descripcion" rows="3" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('meta_descripcion', $registro->meta_descripcion) }}</textarea>
        </label>
        <button class="fuente-ui rounded-full bg-marca px-5 py-3 font-semibold text-white" type="submit">Guardar marca</button>
    </form>
@endsection
