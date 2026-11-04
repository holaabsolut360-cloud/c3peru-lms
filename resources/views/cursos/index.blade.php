@extends('layouts.publico')
@section('titulo', 'Catálogo de cursos · '.$marca->nombre_comercial)
@section('descripcion', 'Cursos de capacitación y certificación en Izaje, Ensayos No Destructivos, Oil & Gas y Mantenimiento.')

@section('contenido')
    <section class="bg-fondo py-14 text-white sm:py-16">
        <div class="mx-auto max-w-6xl px-4 text-center sm:px-6 lg:px-8">
            <p class="fuente-ui text-sm font-semibold uppercase tracking-wider text-acento">Oferta académica</p>
            <h1 class="mt-3 text-3xl font-bold text-white sm:text-4xl">Catálogo de cursos 2026</h1>
            <p class="mx-auto mt-3 max-w-2xl leading-relaxed text-white/75">Programas de capacitación y certificación en Izaje, Ensayos No Destructivos, Oil &amp; Gas y Mantenimiento.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-12 sm:py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <nav class="mb-10 flex flex-wrap justify-center gap-2.5" aria-label="Filtrar cursos por especialidad">
                <a class="fuente-ui rounded-full border px-4 py-2.5 text-sm font-semibold transition-colors {{ $categoriaActiva === '' ? 'border-marca bg-marca text-white' : 'border-slate-200 bg-white text-fondo/75 hover:border-marca hover:text-marca' }}" href="{{ route('cursos.index') }}" @if ($categoriaActiva === '') aria-current="page" @endif>
                    Todas las especialidades
                </a>
            @foreach ($categorias as $categoria)
                    <a class="fuente-ui rounded-full border px-4 py-2.5 text-sm font-semibold transition-colors {{ $categoriaActiva === $categoria->slug ? 'border-marca bg-marca text-white' : 'border-slate-200 bg-white text-fondo/75 hover:border-marca hover:text-marca' }}" href="{{ route('cursos.index', ['categoria' => $categoria->slug]) }}" @if ($categoriaActiva === $categoria->slug) aria-current="page" @endif>
                        {{ $categoria->nombre }}
                    </a>
            @endforeach
            </nav>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($cursos as $curso)
                    <article class="group flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition-shadow hover:shadow-lg">
                        <a class="relative block aspect-[16/10] overflow-hidden bg-fondo" href="{{ route('cursos.ver', $curso) }}">
                            <img src="{{ $curso->imagenPortada() }}" alt="{{ $curso->titulo }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            <span class="fuente-ui absolute left-4 top-4 rounded-md bg-white/95 px-3 py-1.5 text-xs font-semibold uppercase text-marca">{{ $curso->categoria?->nombre ?? 'General' }}</span>
                        </a>

                        <div class="flex flex-1 flex-col p-5">
                            <h2 class="text-xl font-bold leading-snug text-fondo group-hover:text-marca">
                                <a class="text-inherit" href="{{ route('cursos.ver', $curso) }}">{{ $curso->titulo }}</a>
                            </h2>
                            <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-fondo/70">{{ $curso->descripcion ?: 'Capacitación técnica con el acompañamiento de especialistas.' }}</p>

                            <div class="mt-5 flex flex-wrap gap-x-4 gap-y-2 border-b border-slate-100 pb-5 text-sm text-fondo/70">
                                @if ($curso->duracion)
                                    <span class="flex items-center gap-1.5"><i class="ti ti-clock text-marca" aria-hidden="true"></i>{{ $curso->duracion }}</span>
                                @endif
                                @if ($curso->modalidad)
                                    <span class="flex items-center gap-1.5"><i class="ti ti-school text-marca" aria-hidden="true"></i>{{ $curso->modalidad }}</span>
                                @endif
                            </div>

                            <div class="mt-auto flex items-end justify-between gap-3 pt-5">
                                <div>
                                    <span class="block text-xs text-fondo/60">{{ $curso->incluye_igv ? 'Precio, incluye IGV' : 'Precio' }}</span>
                                    <span class="mt-1 block text-xl font-bold text-fondo">{{ $curso->sePuedeComprar() ? $curso->precioFormateado() : 'Consultar' }}</span>
                                </div>
                                <a class="fuente-ui inline-flex shrink-0 items-center gap-2 rounded-lg bg-marca px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-marca-2" href="{{ route('cursos.ver', $curso) }}">
                                    Ver detalles <i class="ti ti-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-lg border border-dashed border-slate-300 bg-white px-6 py-14 text-center">
                        <i class="ti ti-search text-4xl text-slate-400" aria-hidden="true"></i>
                        <p class="mt-3 font-medium text-fondo/75">No hay cursos publicados en esta especialidad.</p>
                        <a class="fuente-ui mt-4 inline-flex items-center gap-2 font-semibold text-marca" href="{{ route('cursos.index') }}">Ver todas las especialidades <i class="ti ti-arrow-right" aria-hidden="true"></i></a>
                    </div>
                @endforelse
            </div>

            @if ($cursos->hasPages())
                <div class="mt-10 border-t border-slate-200 pt-6">
                    <nav class="flex items-center justify-between gap-4" aria-label="Paginación del catálogo">
                        @if ($cursos->onFirstPage())
                            <span class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-400" aria-disabled="true">
                                <i class="ti ti-arrow-left" aria-hidden="true"></i> Anterior
                            </span>
                        @else
                            <a class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-marca hover:border-marca" href="{{ $cursos->previousPageUrl() }}">
                                <i class="ti ti-arrow-left" aria-hidden="true"></i> Anterior
                            </a>
                        @endif

                        <p class="fuente-ui text-center text-sm text-fondo/65">Página {{ $cursos->currentPage() }} de {{ $cursos->lastPage() }}</p>

                        @if ($cursos->hasMorePages())
                            <a class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-marca hover:border-marca" href="{{ $cursos->nextPageUrl() }}">
                                Siguiente <i class="ti ti-arrow-right" aria-hidden="true"></i>
                            </a>
                        @else
                            <span class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-400" aria-disabled="true">
                                Siguiente <i class="ti ti-arrow-right" aria-hidden="true"></i>
                            </span>
                        @endif
                    </nav>
                </div>
            @endif
        </div>
    </section>
@endsection
