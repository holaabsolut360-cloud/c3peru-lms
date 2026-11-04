@extends('layouts.panel')
@section('titulo', 'Calificar entrega')

@section('contenido')
    <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('docente.tareas.entregas', [$entrega->tarea->curso, $entrega->tarea]) }}">← Volver a entregas</a>
    <h1 class="mt-3 text-3xl font-semibold text-marca">Calificar entrega</h1>
    <p class="mt-1 text-sm text-fondo/70">{{ $entrega->alumno->name }} · {{ $entrega->tarea->titulo }}</p>

    <div class="mt-5 grid gap-5 lg:grid-cols-[1fr_24rem]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5">
            <h2 class="text-lg font-semibold text-fondo">Trabajo del alumno</h2>
            @if ($entrega->archivo_path)
                <a class="mt-3 inline-flex items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('tareas.entregas.descargar', $entrega) }}"><i class="ti ti-download" aria-hidden="true"></i> Descargar {{ $entrega->archivo_nombre }}</a>
            @endif
            @if ($entrega->comentario)
                <div class="mt-4 rounded-xl bg-slate-50 p-4"><h3 class="text-sm font-semibold">Comentario del alumno</h3><p class="mt-2 whitespace-pre-line text-sm text-fondo/80">{{ $entrega->comentario }}</p></div>
            @endif
        </section>

        <form method="post" action="{{ route('docente.tareas.calificar.guardar', $entrega) }}" class="rounded-2xl border border-slate-200 bg-white p-5">
            @csrf
            <h2 class="text-lg font-semibold text-fondo">Rúbrica de evaluación</h2>
            @foreach ($rubricas as $rubrica)
                @php $calificacion = $calificaciones->get($rubrica->id); @endphp
                <fieldset class="mt-4 border-b border-slate-100 pb-4 last:border-0">
                    <legend class="font-semibold text-fondo">{{ $rubrica->criterio }} <span class="text-xs font-normal text-fondo/60">· máximo {{ $rubrica->puntaje_maximo }} pts</span></legend>
                    <div class="mt-2 space-y-1 text-xs text-fondo/70">
                        @if ($rubrica->descripcion_nivel_alto)<p><strong>Nivel alto:</strong> {{ $rubrica->descripcion_nivel_alto }}</p>@endif
                        @if ($rubrica->descripcion_nivel_medio)<p><strong>Nivel medio:</strong> {{ $rubrica->descripcion_nivel_medio }}</p>@endif
                        @if ($rubrica->descripcion_nivel_bajo)<p><strong>Nivel bajo:</strong> {{ $rubrica->descripcion_nivel_bajo }}</p>@endif
                    </div>
                    <label class="mt-3 block text-xs font-medium text-fondo">Puntaje obtenido
                        <input data-puntaje-rubrica name="rubricas[{{ $rubrica->id }}][puntaje]" type="number" min="0" max="{{ $rubrica->puntaje_maximo }}" required value="{{ old('rubricas.'.$rubrica->id.'.puntaje', $calificacion?->puntaje_obtenido ?? 0) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </label>
                    <label class="mt-2 block text-xs font-medium text-fondo">Comentario para este criterio
                        <textarea name="rubricas[{{ $rubrica->id }}][comentario]" rows="2" maxlength="2000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('rubricas.'.$rubrica->id.'.comentario', $calificacion?->comentario) }}</textarea>
                    </label>
                </fieldset>
            @endforeach
            <label class="mt-4 block text-sm font-medium text-fondo">Comentario general
                <textarea name="comentario_docente" rows="3" maxlength="4000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">{{ old('comentario_docente', $entrega->comentario_docente) }}</textarea>
            </label>
            <p class="mt-4 rounded-xl bg-slate-50 p-3 text-sm font-semibold text-fondo">Puntaje total: <span id="total-puntaje">{{ $entrega->puntaje_total ?? 0 }}</span> / {{ $totalRubrica }}</p>
            <button class="mt-4 w-full rounded-lg bg-marca px-4 py-3 text-sm font-semibold text-white hover:bg-marca-2" type="submit">Guardar calificación</button>
        </form>
    </div>
    <script>
        (() => {
            const inputs = document.querySelectorAll('[data-puntaje-rubrica]');
            const total = document.getElementById('total-puntaje');
            const actualizar = () => { total.textContent = Array.from(inputs).reduce((suma, campo) => suma + (Number(campo.value) || 0), 0); };
            inputs.forEach((campo) => campo.addEventListener('input', actualizar));
            actualizar();
        })();
    </script>
@endsection
