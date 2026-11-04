@extends('layouts.panel')
@section('titulo', 'Recursos · '.$curso->titulo)

@section('contenido')
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a class="text-sm font-semibold text-marca underline" href="{{ route('aula.curso', $curso) }}">Volver al curso</a>
            <h1 class="mt-2 text-3xl text-marca">Biblioteca de recursos</h1>
            <p class="mt-1 text-sm text-fondo/65">{{ $curso->titulo }} · {{ $cantidadMateriales }} {{ $cantidadMateriales === 1 ? 'archivo disponible' : 'archivos disponibles' }}</p>
        </div>
        @if ($cantidadMateriales > 0)
            <a class="fuente-ui inline-flex items-center gap-2 rounded-lg bg-marca px-4 py-2.5 text-sm font-semibold text-white hover:bg-marca-2" href="{{ route('aula.recursos.zip', $curso) }}">
                <i class="ti ti-file-zip" aria-hidden="true"></i> Descargar todo en ZIP
            </a>
        @endif
    </div>

    @if ($cantidadMateriales === 0)
        <div class="mt-6 rounded-xl border border-dashed border-slate-300 px-4 py-10 text-center">
            <i class="ti ti-folder-off text-3xl text-slate-400" aria-hidden="true"></i>
            <p class="mt-2 text-sm text-fondo/65">Todavía no hay archivos para este curso.</p>
        </div>
    @else
        <div class="mt-6 space-y-5">
            @foreach ($lecciones as $leccion)
                @if ($leccion->materiales->isNotEmpty())
                    <section class="rounded-xl border border-slate-200 bg-white p-5" aria-labelledby="recursos-leccion-{{ $leccion->id }}">
                        <h2 id="recursos-leccion-{{ $leccion->id }}" class="text-lg font-bold text-fondo">{{ $leccion->titulo }}</h2>
                        <ul class="mt-3 divide-y divide-slate-100">
                            @foreach ($leccion->materiales as $material)
                                <li class="flex flex-wrap items-center justify-between gap-3 py-3">
                                    <div class="flex min-w-0 items-center gap-3">
                                        <i class="ti ti-file-description text-xl text-marca" aria-hidden="true"></i>
                                        <div class="min-w-0">
                                            <p class="break-words text-sm font-semibold text-fondo">{{ $material->nombre_original }}</p>
                                            <p class="text-xs text-fondo/60">{{ number_format($material->tamano_bytes / 1048576, 2) }} MB</p>
                                        </div>
                                    </div>
                                    <a class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-marca px-3 py-2 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('aula.materiales.descargar', $material) }}">
                                        <i class="ti ti-download" aria-hidden="true"></i> Descargar
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            @endforeach
        </div>
    @endif
@endsection
