@extends('layouts.panel')
@section('titulo', 'Anuncios · '.$curso->titulo)

@section('contenido')
    <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('docente.inicio') }}">← Volver a mis cursos</a>
    <h1 class="mt-3 text-3xl font-semibold text-marca">Tablón de anuncios</h1>
    <p class="mt-1 text-sm text-fondo/70">{{ $curso->titulo }} · {{ $alumnosActivos }} alumnos con matrícula activa.</p>

    <a class="mt-5 inline-flex items-center gap-2 rounded-lg bg-marca px-5 py-3 text-sm font-semibold text-white hover:bg-marca-2" href="{{ route('docente.anuncios.crear', $curso) }}">
        <i class="ti ti-plus" aria-hidden="true"></i> Crear anuncio
    </a>

    <section class="mt-8 max-w-3xl">
        <h2 class="text-xl font-semibold text-fondo">Anuncios publicados</h2>
        <div class="mt-3 space-y-3">
            @forelse ($anuncios as $anuncio)
                <article class="rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-2">
                        <h3 class="font-semibold text-fondo">{{ $anuncio->titulo }}</h3>
                        <time class="text-xs text-fondo/60" datetime="{{ $anuncio->publicado_en?->toIso8601String() }}">{{ $anuncio->publicado_en?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</time>
                    </div>
                    <p class="mt-3 whitespace-pre-line text-sm text-fondo/80">{{ $anuncio->contenido }}</p>
                    @if ($anuncio->enviar_whatsapp)
                        <a class="mt-4 inline-flex rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800" href="https://wa.me/?text={{ rawurlencode($anuncio->titulo.' · '.$anuncio->contenido.' '.route('aula.curso', $curso)) }}" target="_blank" rel="noopener">Compartir por WhatsApp</a>
                    @endif
                </article>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 p-5 text-sm text-fondo/65">Aún no hay anuncios publicados.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $anuncios->links() }}</div>
    </section>
@endsection
