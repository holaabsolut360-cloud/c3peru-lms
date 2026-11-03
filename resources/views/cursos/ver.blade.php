@extends('layouts.publico')
@section('titulo', $curso->titulo.' · '.$marca->nombre_comercial)
@section('descripcion', $curso->titulo.' de '.$curso->categoria->nombre.'. '.$curso->precioFormateado().'. '.$marca->nombre_comercial)

@section('contenido')
    <article class="mx-auto max-w-3xl px-4 py-12">
        <p class="fuente-ui text-sm font-semibold uppercase text-marca-2">{{ $curso->categoria->nombre }}</p>
        <h1 class="mt-2 text-4xl text-marca">{{ $curso->titulo }}</h1>
        @if ($curso->nivel)<p class="mt-2 text-lg">{{ $curso->nivel }}</p>@endif
        <dl class="mt-6 space-y-2">
            @if ($curso->duracion)<div><dt class="inline font-semibold">Duración:</dt> <dd class="inline">{{ $curso->duracion }}</dd></div>@endif
            @if ($curso->modalidad)<div><dt class="inline font-semibold">Modalidad:</dt> <dd class="inline">{{ $curso->modalidad }}</dd></div>@endif
            @if ($curso->fechas)<div><dt class="inline font-semibold">Fechas:</dt> <dd class="inline">{{ $curso->fechas }}</dd></div>@endif
            @if ($curso->certificacion)<div><dt class="inline font-semibold">Certificación:</dt> <dd class="inline">{{ $curso->certificacion }}</dd></div>@endif
        </dl>
        <p class="mt-6 text-3xl text-marca">{{ $curso->precioFormateado() }}</p>
        @if ($curso->descripcion)
            <p class="mt-4 whitespace-pre-line">{{ $curso->descripcion }}</p>
        @endif
        <div class="mt-8">
            @if ($curso->sePuedeComprar())
                <a class="fuente-ui inline-block rounded-full bg-marca px-5 py-3 font-semibold text-white" href="{{ route('compra.crear', $curso) }}">Inscribirme con comprobante</a>
                <p class="mt-2 text-sm">El acceso al aula se habilita cuando administración aprueba el comprobante.</p>
            @else
                <a class="boton-whatsapp fuente-ui inline-flex items-center gap-2 rounded-full px-5 py-3 font-semibold" href="{{ $marca->enlaceWhatsapp('Hola, quiero consultar el curso '.$curso->titulo) }}" target="_blank" rel="noopener">
                    <i class="ti ti-brand-whatsapp"></i> Consultar por WhatsApp
                </a>
                <p class="mt-2 text-sm">Este curso no tiene precio publicado y no se puede comprar en línea.</p>
            @endif
        </div>
    </article>
@endsection
