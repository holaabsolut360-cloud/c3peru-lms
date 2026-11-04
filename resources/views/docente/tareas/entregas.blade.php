@extends('layouts.panel')
@section('titulo', 'Entregas · '.$tarea->titulo)

@section('contenido')
    <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('docente.tareas.index', $curso) }}">← Volver a tareas</a>
    <h1 class="mt-3 text-3xl font-semibold text-marca">Entregas: {{ $tarea->titulo }}</h1>
    <p class="mt-1 text-sm text-fondo/70">{{ $curso->titulo }} · {{ $tarea->rubricas->count() }} criterios · {{ $tarea->rubricas->sum('puntaje_maximo') }} puntos posibles</p>

    <div class="mt-5 space-y-3">
        @forelse ($entregas as $entrega)
            <article class="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4">
                <div>
                    <p class="font-semibold text-fondo">{{ $entrega->alumno->name }}</p>
                    <p class="text-xs text-fondo/65">Entregó {{ $entrega->entregado_en->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</p>
                    @if ($entrega->calificada_en)
                        <p class="mt-1 text-sm font-semibold text-emerald-800">{{ $entrega->puntaje_total }} / {{ $tarea->rubricas->sum('puntaje_maximo') }} puntos</p>
                    @else
                        <p class="mt-1 text-sm text-amber-800">Pendiente de calificación</p>
                    @endif
                </div>
                <a class="rounded-lg bg-marca px-4 py-2 text-sm font-semibold text-white hover:bg-marca-2" href="{{ route('docente.tareas.calificar', $entrega) }}">{{ $entrega->calificada_en ? 'Revisar calificación' : 'Calificar con rúbrica' }}</a>
            </article>
        @empty
            <p class="rounded-xl border border-dashed border-slate-300 p-5 text-sm text-fondo/65">Todavía no hay entregas para esta tarea.</p>
        @endforelse
    </div>
    <div class="mt-5">{{ $entregas->links() }}</div>
@endsection
