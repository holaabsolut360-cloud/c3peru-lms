@extends('layouts.panel')
@section('titulo', 'Tareas · '.$curso->titulo)

@section('contenido')
    <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('docente.inicio') }}">← Volver a mis cursos</a>
    <h1 class="mt-3 text-3xl font-semibold text-marca">Tareas y rúbricas</h1>
    <p class="mt-1 text-sm text-fondo/70">{{ $curso->titulo }} · publica actividades y evalúa entregas con criterios claros.</p>

    <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
        <h2 class="text-xl font-semibold text-fondo">Nueva tarea</h2>
        <form method="post" action="{{ route('docente.tareas.guardar', $curso) }}" class="mt-4 space-y-4">
            @csrf
            <label class="block text-sm font-medium text-fondo">Título
                <input name="titulo" value="{{ old('titulo') }}" required maxlength="180" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
            </label>
            <label class="block text-sm font-medium text-fondo">Instrucciones
                <textarea name="instrucciones" rows="4" required maxlength="12000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">{{ old('instrucciones') }}</textarea>
            </label>
            <label class="block max-w-sm text-sm font-medium text-fondo">Fecha límite (opcional)
                <input name="fecha_entrega" type="datetime-local" value="{{ old('fecha_entrega') }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
            </label>

            <div>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h3 class="font-semibold text-fondo">Criterios de evaluación</h3>
                        <p class="text-xs text-fondo/65">Cada criterio incluye puntaje máximo y descriptores de nivel.</p>
                    </div>
                    <button id="agregar-criterio" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-marca hover:bg-slate-50" type="button">Agregar criterio</button>
                </div>
                <div id="criterios-rubrica" class="mt-3 space-y-3">
                    <fieldset data-rubrica-row class="rounded-xl border border-slate-200 p-4">
                        <legend class="px-1 text-sm font-semibold text-fondo">Criterio 1</legend>
                        <div class="grid gap-3 sm:grid-cols-[1fr_10rem]">
                            <label class="text-xs font-medium text-fondo">Nombre del criterio
                                <input name="criterios[0][criterio]" value="{{ old('criterios.0.criterio') }}" required maxlength="180" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </label>
                            <label class="text-xs font-medium text-fondo">Puntaje máximo
                                <input name="criterios[0][puntaje_maximo]" value="{{ old('criterios.0.puntaje_maximo', 5) }}" required type="number" min="1" max="100" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                            </label>
                        </div>
                        <div class="mt-3 grid gap-3 md:grid-cols-3">
                            <label class="text-xs text-fondo">Nivel alto<textarea name="criterios[0][descripcion_nivel_alto]" rows="2" maxlength="2000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('criterios.0.descripcion_nivel_alto') }}</textarea></label>
                            <label class="text-xs text-fondo">Nivel medio<textarea name="criterios[0][descripcion_nivel_medio]" rows="2" maxlength="2000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('criterios.0.descripcion_nivel_medio') }}</textarea></label>
                            <label class="text-xs text-fondo">Nivel bajo<textarea name="criterios[0][descripcion_nivel_bajo]" rows="2" maxlength="2000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('criterios.0.descripcion_nivel_bajo') }}</textarea></label>
                        </div>
                    </fieldset>
                </div>
                <template id="plantilla-criterio">
                    <fieldset data-rubrica-row class="rounded-xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between gap-2">
                            <legend class="text-sm font-semibold text-fondo">Criterio adicional</legend>
                            <button data-quitar-criterio class="text-xs font-semibold text-rose-700 hover:underline" type="button">Quitar</button>
                        </div>
                        <div class="mt-2 grid gap-3 sm:grid-cols-[1fr_10rem]">
                            <label class="text-xs font-medium text-fondo">Nombre del criterio<input name="criterios[__INDEX__][criterio]" required maxlength="180" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></label>
                            <label class="text-xs font-medium text-fondo">Puntaje máximo<input name="criterios[__INDEX__][puntaje_maximo]" required type="number" min="1" max="100" value="5" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></label>
                        </div>
                        <div class="mt-3 grid gap-3 md:grid-cols-3">
                            <label class="text-xs text-fondo">Nivel alto<textarea name="criterios[__INDEX__][descripcion_nivel_alto]" rows="2" maxlength="2000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea></label>
                            <label class="text-xs text-fondo">Nivel medio<textarea name="criterios[__INDEX__][descripcion_nivel_medio]" rows="2" maxlength="2000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea></label>
                            <label class="text-xs text-fondo">Nivel bajo<textarea name="criterios[__INDEX__][descripcion_nivel_bajo]" rows="2" maxlength="2000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea></label>
                        </div>
                    </fieldset>
                </template>
            </div>
            <button class="rounded-lg bg-marca px-5 py-2.5 text-sm font-semibold text-white hover:bg-marca-2" type="submit">Publicar tarea</button>
        </form>
    </section>

    <section class="mt-8">
        <h2 class="text-xl font-semibold text-fondo">Tareas publicadas</h2>
        <div class="mt-3 space-y-3">
            @forelse ($tareas as $tarea)
                <article class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4">
                    <div>
                        <h3 class="font-semibold text-fondo">{{ $tarea->titulo }}</h3>
                        <p class="mt-1 text-xs text-fondo/65">{{ $tarea->rubricas_count }} criterios · {{ $tarea->entregas_count }} entregas · {{ $tarea->entregas_calificadas_count }} calificadas</p>
                        @if ($tarea->fecha_entrega)<p class="mt-1 text-xs text-fondo/65">Fecha límite: {{ $tarea->fecha_entrega->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</p>@endif
                    </div>
                    <a class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('docente.tareas.entregas', [$curso, $tarea]) }}">Ver entregas</a>
                </article>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 p-5 text-sm text-fondo/65">Aún no publicas tareas para este curso.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $tareas->links() }}</div>
    </section>
    <script>
        (() => {
            const botonAgregar = document.getElementById('agregar-criterio');
            const listaCriterios = document.getElementById('criterios-rubrica');
            const plantilla = document.getElementById('plantilla-criterio');
            let indice = 1;

            botonAgregar?.addEventListener('click', () => {
                const contenido = plantilla.content.cloneNode(true);
                contenido.querySelectorAll('[name]').forEach((campo) => {
                    campo.name = campo.name.replaceAll('__INDEX__', String(indice));
                });
                listaCriterios.append(contenido);
                indice += 1;
            });

            listaCriterios?.addEventListener('click', (event) => {
                if (event.target.closest('[data-quitar-criterio]')) {
                    event.target.closest('[data-rubrica-row]').remove();
                }
            });
        })();
    </script>
@endsection
