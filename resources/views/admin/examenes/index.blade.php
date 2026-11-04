@extends('layouts.panel')
@section('titulo', 'Exámenes · '.$curso->titulo)

@section('contenido')
    <div class="mx-auto max-w-5xl">
        <a class="fuente-ui text-sm font-semibold text-marca" href="{{ route('admin.cursos') }}">Volver a cursos</a>
        <header class="mt-4 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="fuente-ui text-xs font-semibold uppercase tracking-wide text-marca">{{ $curso->titulo }}</p>
                <h1 class="mt-1 text-3xl font-bold text-fondo">Exámenes y calificaciones</h1>
            </div>
            <a class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-marca hover:bg-white" href="{{ route('admin.calificaciones.exportar', $curso) }}">
                <i class="ti ti-file-spreadsheet" aria-hidden="true"></i> Exportar Excel
            </a>
        </header>

        <section class="mt-6 rounded-xl border border-slate-200 bg-white p-5 sm:p-7">
            <h2 class="text-xl font-bold text-fondo">Crear examen</h2>
            <form method="post" action="{{ route('admin.cursos.examenes.guardar', $curso) }}" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold text-fondo" for="titulo">Título</label>
                    <input id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="180" class="w-full rounded-lg border border-slate-300 px-4 py-3">
                    @error('titulo')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-fondo" for="instrucciones">Instrucciones <span class="text-fondo/60">(opcional)</span></label>
                    <textarea id="instrucciones" name="instrucciones" rows="2" maxlength="4000" class="w-full rounded-lg border border-slate-300 px-4 py-3">{{ old('instrucciones') }}</textarea>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <label class="text-sm font-semibold text-fondo" for="duracion_minutos">Duración en minutos
                        <input id="duracion_minutos" name="duracion_minutos" type="number" min="1" max="240" value="{{ old('duracion_minutos', 30) }}" required class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3">
                    </label>
                    <label class="text-sm font-semibold text-fondo" for="puntaje_aprobatorio">Nota aprobatoria / 20
                        <input id="puntaje_aprobatorio" name="puntaje_aprobatorio" type="number" min="0" max="20" step="0.25" value="{{ old('puntaje_aprobatorio', 14) }}" required class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3">
                    </label>
                    <label class="text-sm font-semibold text-fondo" for="intentos_permitidos">Intentos permitidos
                        <input id="intentos_permitidos" name="intentos_permitidos" type="number" min="1" max="10" value="{{ old('intentos_permitidos', 1) }}" required class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3">
                    </label>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-semibold text-fondo" for="preguntas">Preguntas</label>
                    <textarea id="preguntas" name="preguntas" rows="8" required class="w-full rounded-lg border border-slate-300 px-4 py-3 font-mono text-sm" placeholder="Enunciado || Opción A || Opción B || Opción C || Opción D || A || 2">{{ old('preguntas') }}</textarea>
                    <p class="mt-2 text-xs leading-relaxed text-fondo/60">Una pregunta por línea: enunciado, cuatro opciones, letra correcta (A-D) y puntaje, separados por <code>||</code>. La respuesta correcta nunca se envía al navegador durante el examen.</p>
                    @error('preguntas')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-2 text-sm font-semibold text-fondo">
                    <input type="checkbox" name="disponible" value="1" @checked(old('disponible')) class="h-4 w-4 rounded border-slate-300 text-marca focus:ring-marca">
                    Publicar inmediatamente
                </label>
                <button class="fuente-ui inline-flex items-center gap-2 rounded-lg bg-marca px-5 py-3 font-semibold text-white hover:bg-marca-2" type="submit">
                    <i class="ti ti-device-floppy" aria-hidden="true"></i> Guardar examen
                </button>
            </form>
        </section>

        <section class="mt-8 space-y-3">
            <h2 class="text-xl font-bold text-fondo">Exámenes del curso</h2>
            @forelse ($examenes as $examen)
                <article class="flex flex-wrap items-center justify-between gap-4 rounded-lg border border-slate-200 bg-white p-4">
                    <div>
                        <h3 class="font-semibold text-fondo">{{ $examen->titulo }}</h3>
                        <p class="text-sm text-fondo/65">{{ $examen->preguntas_count }} preguntas · {{ $examen->intentos_completados_count }} intentos completados · {{ $examen->disponible ? 'Publicado' : 'Borrador' }}</p>
                    </div>
                </article>
            @empty
                <p class="rounded-lg border border-dashed border-slate-300 p-6 text-sm text-fondo/65">Todavía no hay exámenes para este curso.</p>
            @endforelse
        </section>
    </div>
@endsection