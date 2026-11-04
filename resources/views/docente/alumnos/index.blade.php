@extends('layouts.panel')
@section('titulo', 'Alumnos · '.$curso->titulo)

@section('contenido')
    <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('docente.inicio') }}">← Volver a mis cursos</a>
    <div class="mt-3 flex flex-wrap items-end justify-between gap-3">
        <div>
            <h1 class="text-3xl font-semibold text-marca">Alumnos de {{ $curso->titulo }}</h1>
            <p class="mt-1 text-sm text-fondo/70">Avance, actividad y señales tempranas para acompañar a cada alumno.</p>
        </div>
        <a class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('docente.tareas.index', $curso) }}">Tareas y rúbricas</a>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <article class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
            <p class="text-3xl font-bold text-emerald-800">{{ $conteo['verde'] }}</p>
            <p class="mt-1 text-sm text-emerald-900">Avanzan a buen ritmo</p>
        </article>
        <article class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
            <p class="text-3xl font-bold text-amber-800">{{ $conteo['amarillo'] }}</p>
            <p class="mt-1 text-sm text-amber-900">Requieren seguimiento</p>
        </article>
        <article class="rounded-2xl border border-rose-200 bg-rose-50 p-5">
            <p class="text-3xl font-bold text-rose-800">{{ $conteo['rojo'] }}</p>
            <p class="mt-1 text-sm text-rose-900">Necesitan atención</p>
        </article>
    </div>

    <nav class="mt-6 flex flex-wrap gap-2" aria-label="Filtrar alumnos por estado">
        <a class="rounded-full px-4 py-2 text-sm font-semibold {{ ! $filtro ? 'bg-marca text-white' : 'bg-slate-100 text-fondo hover:bg-slate-200' }}" href="{{ route('docente.alumnos.index', $curso) }}">Todos ({{ $totalAlumnos }})</a>
        @foreach (['verde' => 'Verdes', 'amarillo' => 'Amarillos', 'rojo' => 'Rojos'] as $estado => $etiqueta)
            <a class="rounded-full px-4 py-2 text-sm font-semibold {{ $filtro === $estado ? 'bg-marca text-white' : 'bg-slate-100 text-fondo hover:bg-slate-200' }}" href="{{ route('docente.alumnos.index', ['curso' => $curso, 'filtro' => $estado]) }}">{{ $etiqueta }} ({{ $conteo[$estado] }})</a>
        @endforeach
    </nav>

    <div class="mt-5 overflow-x-auto rounded-2xl border border-slate-200 bg-white">
        <table class="w-full min-w-[850px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-fondo/65">
                <tr>
                    <th class="px-4 py-3">Estado</th>
                    <th class="px-4 py-3">Alumno</th>
                    <th class="px-4 py-3">Progreso</th>
                    <th class="px-4 py-3">Última actividad</th>
                    <th class="px-4 py-3">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($alumnosConEstado as $fila)
                    @php
                        $estado = $fila['semaforo']['estado'];
                        $clasesEstado = match ($estado) {
                            'verde' => 'bg-emerald-100 text-emerald-900',
                            'rojo' => 'bg-rose-100 text-rose-900',
                            default => 'bg-amber-100 text-amber-900',
                        };
                    @endphp
                    <tr>
                        <td class="px-4 py-3">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $clasesEstado }}">{{ ucfirst($estado) }}</span>
                            <p class="mt-1 text-xs text-fondo/65">{{ $fila['semaforo']['mensaje'] }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-fondo">{{ $fila['alumno']->name }}</p>
                            <p class="text-xs text-fondo/60">{{ $fila['alumno']->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <label class="sr-only" for="avance-{{ $fila['alumno']->id }}">Avance de {{ $fila['alumno']->name }}</label>
                            <progress id="avance-{{ $fila['alumno']->id }}" class="h-2 w-28 accent-marca" max="100" value="{{ $fila['semaforo']['progreso'] }}">{{ $fila['semaforo']['progreso'] }}%</progress>
                            <span class="ml-2">{{ number_format($fila['semaforo']['progreso'], 0) }}%</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-fondo/70">
                            @if ($fila['semaforo']['ultima_actividad'])
                                {{ $fila['semaforo']['ultima_actividad']->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                            @else
                                Sin actividad registrada
                            @endif
                            <span class="mt-1 block">Hace {{ $fila['semaforo']['dias_sin_actividad'] }} días</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-2">
                                <form method="post" action="{{ route('docente.alumnos.recordatorio', [$curso, $fila['alumno']]) }}">
                                    @csrf
                                    <button class="rounded-lg bg-marca px-3 py-2 text-xs font-semibold text-white hover:bg-marca-2" type="submit">Enviar recordatorio</button>
                                </form>
                                @if ($fila['whatsapp_url'])
                                    <a class="rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800" href="{{ $fila['whatsapp_url'] }}" target="_blank" rel="noopener">WhatsApp</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td class="px-4 py-8 text-center text-fondo/65" colspan="5">No hay alumnos para este filtro.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $alumnosConEstado->links() }}</div>
@endsection
