@props(['contexto' => 'general'])

@php
    $mensajes = [
        'general' => 'Hola, tengo una consulta sobre los cursos de C3 Perú.',
        'curso' => 'Hola, tengo una consulta sobre el curso que estoy revisando.',
        'inscripcion' => 'Hola, quiero información para inscribirme en un curso.',
        'soporte' => 'Hola, necesito ayuda con mi aula virtual.',
        'cotizacion' => 'Hola, quiero solicitar una cotización corporativa.',
    ];
    $mensaje = $mensajes[$contexto] ?? $mensajes['general'];
@endphp

<a
    class="boton-whatsapp group"
    href="{{ $marca->enlaceWhatsapp($mensaje) }}"
    target="_blank"
    rel="noopener noreferrer"
    aria-label="Contactar a C3 Perú por WhatsApp"
>
    <i class="ti ti-brand-whatsapp text-2xl" aria-hidden="true"></i>
    <span class="hidden lg:inline">WhatsApp</span>
    <span class="pointer-events-none absolute bottom-full right-0 mb-3 whitespace-nowrap rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white opacity-0 shadow-lg transition-opacity group-hover:opacity-100 group-focus-visible:opacity-100" role="tooltip">
        ¿Necesitas ayuda?
    </span>
    <span class="absolute right-0 top-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-emerald-300" aria-hidden="true"></span>
</a>
