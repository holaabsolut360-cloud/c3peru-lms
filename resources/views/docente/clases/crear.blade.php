@extends('layouts.panel')
@section('titulo', 'Programar clase · '.$curso->titulo)

@section('contenido')
    <div class="mx-auto max-w-4xl">
        <header class="mb-7">
            <a class="fuente-ui inline-flex items-center gap-2 text-sm font-semibold text-marca hover:text-marca-2" href="{{ route('docente.inicio') }}">
                <i class="ti ti-arrow-left" aria-hidden="true"></i> Cursos asignados
            </a>
            <p class="fuente-ui mt-5 text-xs font-semibold uppercase tracking-wide text-marca">{{ $curso->categoria?->nombre ?? 'Curso' }}</p>
            <h1 class="mt-1 text-3xl font-bold text-fondo">Programar clase en vivo</h1>
            <p class="mt-2 text-fondo/70">{{ $curso->titulo }}</p>
        </header>

        <section class="rounded-xl border border-slate-200 bg-white p-5 sm:p-7">
            <form action="{{ route('docente.clases.guardar', $curso) }}" method="post" class="space-y-6">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold text-fondo" for="titulo">Título de la sesión <span class="text-red-700">*</span></label>
                    <input id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="180" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                    @error('titulo')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>

                @if ($lecciones->isNotEmpty())
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="leccion_id">Lección relacionada <span class="text-fondo/55">(opcional)</span></label>
                        <select id="leccion_id" name="leccion_id" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                            <option value="">Sin lección específica</option>
                            @foreach ($lecciones as $leccion)
                                <option value="{{ $leccion->id }}" @selected((string) old('leccion_id') === (string) $leccion->id)>{{ $leccion->titulo }}</option>
                            @endforeach
                        </select>
                        @error('leccion_id')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                @endif

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="fecha_inicio">Fecha y hora de inicio <span class="text-red-700">*</span></label>
                        <input id="fecha_inicio" name="fecha_inicio" type="datetime-local" value="{{ old('fecha_inicio') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                        @error('fecha_inicio')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="fecha_fin">Fecha y hora de fin <span class="text-red-700">*</span></label>
                        <input id="fecha_fin" name="fecha_fin" type="datetime-local" value="{{ old('fecha_fin') }}" required class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                        @error('fecha_fin')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-fondo" for="descripcion">Descripción <span class="text-fondo/55">(opcional)</span></label>
                    <textarea id="descripcion" name="descripcion" rows="3" maxlength="4000" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>

                <div class="rounded-lg border border-sky-200 bg-sky-50 p-4">
                    <p class="flex items-center gap-2 text-sm font-semibold text-sky-950"><i class="ti ti-brand-teams text-lg" aria-hidden="true"></i> Enlace de prueba</p>
                    <p class="mt-1 text-sm leading-relaxed text-sky-900">Al guardar se generará un enlace simulado de Teams para revisar el aula. No crea una reunión real ni envía invitaciones.</p>
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a class="fuente-ui inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-3 font-semibold text-fondo hover:bg-slate-50" href="{{ route('docente.inicio') }}">Cancelar</a>
                    <button class="fuente-ui inline-flex items-center justify-center gap-2 rounded-lg bg-marca px-5 py-3 font-semibold text-white hover:bg-marca-2" type="submit">
                        <i class="ti ti-calendar-plus" aria-hidden="true"></i> Guardar clase
                    </button>
                </div>
            </form>
        </section>
    </div>
@endsection