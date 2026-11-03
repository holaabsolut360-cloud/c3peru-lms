@extends('layouts.panel')
@section('titulo', 'Docente')

@section('contenido')
    <h1 class="text-3xl text-marca">Cursos asignados</h1>
    @forelse ($cursos as $curso)
        <article class="mt-4 rounded-2xl border p-4">
            <h2 class="text-xl">{{ $curso->titulo }}</h2>
            <p class="text-sm">{{ $curso->categoria->nombre }} · {{ $curso->lecciones_count }} lecciones</p>
        </article>
    @empty
        <p class="mt-4">Aún no tienes cursos asignados. Administración puede vincularte desde la ficha del curso cuando los instructores estén confirmados.</p>
    @endforelse
@endsection
