@extends('layouts.panel')
@section('titulo', 'Docente')

@section('contenido')
    <h1 class="text-3xl text-marca">Cursos asignados</h1>
    @forelse ($cursos as $curso)
        <article class="mt-4 flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-marca">{{ $curso->titulo }}</h2>
                <p class="text-sm text-fondo/70">{{ $curso->categoria->nombre }} · {{ $curso->lecciones_count }} lecciones · {{ $curso->clases_en_vivo_count }} clases en vivo</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a class="fuente-ui inline-flex items-center justify-center gap-2 rounded-lg bg-marca px-4 py-2.5 text-sm font-semibold text-white hover:bg-marca-2" href="{{ route('docente.clases.crear', $curso) }}">
                    <i class="ti ti-calendar-plus" aria-hidden="true"></i> Programar clase
                </a>
                <a class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('docente.alumnos.index', $curso) }}">Alumnos</a>
                <a class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('docente.tareas.index', $curso) }}">Tareas y rúbricas</a>
                <a class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('docente.contenido.index', $curso) }}">Contenido del curso</a>
                <a class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('docente.anuncios.index', $curso) }}">Anuncios</a>
            </div>
        </article>
    @empty
        <p class="mt-4">Aún no tienes cursos asignados. Administración puede vincularte desde la ficha del curso cuando los instructores estén confirmados.</p>
    @endforelse

    <section class="mt-10" aria-labelledby="seguimiento-alumnos">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <h2 id="seguimiento-alumnos" class="text-2xl font-semibold text-marca">Semáforo de alumnos</h2>
                <p class="mt-1 text-sm text-fondo/70">Seguimiento de avance y actividad en tus cursos.</p>
            </div>
            <div class="flex flex-wrap gap-2 text-xs font-semibold">
                <span class="rounded-full bg-emerald-100 px-3 py-1 text-emerald-900">Verde · buen ritmo</span>
                <span class="rounded-full bg-amber-100 px-3 py-1 text-amber-900">Amarillo · seguimiento</span>
                <span class="rounded-full bg-rose-100 px-3 py-1 text-rose-900">Rojo · atención</span>
            </div>
        </div>

        <div class="mt-5 overflow-x-auto rounded-2xl border border-slate-200 bg-white">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-fondo/65">
                    <tr>
                        <th class="px-4 py-3">Alumno</th>
                        <th class="px-4 py-3">Curso</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Avance</th>
                        <th class="px-4 py-3">Última actividad</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($matriculas as $matricula)
                        @php
                            $estadoAlumno = $estadosAlumnos[$matricula->id];
                            $clasesEstado = match ($estadoAlumno['estado']) {
                                'verde' => 'bg-emerald-100 text-emerald-900',
                                'rojo' => 'bg-rose-100 text-rose-900',
                                default => 'bg-amber-100 text-amber-900',
                            };
                        @endphp
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-fondo">{{ $matricula->usuario->name }}</p>
                            </td>
                            <td class="px-4 py-3">{{ $matricula->curso->titulo }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $clasesEstado }}">{{ ucfirst($estadoAlumno['estado']) }}</span>
                                <p class="mt-1 text-xs text-fondo/65">{{ $estadoAlumno['mensaje'] }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <label class="sr-only" for="avance-{{ $matricula->id }}">Avance de {{ $matricula->usuario->name }}</label>
                                <progress id="avance-{{ $matricula->id }}" class="h-2 w-28 accent-marca" max="100" value="{{ $estadoAlumno['progreso'] }}">{{ $estadoAlumno['progreso'] }}%</progress>
                                <span class="ml-2 text-xs">{{ number_format($estadoAlumno['progreso'], 0) }}%</span>
                            </td>
                            <td class="px-4 py-3 text-xs text-fondo/70">
                                @if ($estadoAlumno['ultima_actividad'])
                                    {{ $estadoAlumno['ultima_actividad']->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                @else
                                    Sin actividad registrada
                                @endif
                                <span class="mt-1 block">{{ $estadoAlumno['dias_sin_actividad'] }} días</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-fondo/65" colspan="5">No hay matrículas activas en tus cursos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $matriculas->links() }}</div>
    </section>
@endsection
