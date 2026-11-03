@extends('layouts.panel')
@section('titulo', 'Cursos')

@section('contenido')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-3xl text-marca">Cursos</h1>
        <a class="rounded-lg bg-marca px-4 py-2 text-sm font-semibold text-white" href="{{ route('admin.cursos.nuevo') }}">Nuevo curso</a>
    </div>
    <div class="mt-6 space-y-3">
        @foreach ($cursos as $curso)
            <article class="flex flex-wrap items-center gap-4 rounded-2xl border p-4">
                <img src="{{ $curso->imagenPortada() }}" alt="" class="h-16 w-24 rounded-lg object-cover">
                <div class="min-w-0 flex-1">
                    <p class="font-semibold">{{ $curso->titulo }}</p>
                    <p class="text-sm text-marca-2">{{ $curso->categoria->nombre }} · {{ $curso->nivel ?: 'Sin nivel' }} · {{ $curso->precioFormateado() }}</p>
                    <p class="text-sm">{{ $curso->publicado ? 'Publicado' : 'Oculto' }} · {{ $curso->modalidad }} · {{ $curso->duracion }}</p>
                </div>
                <a href="{{ route('admin.cursos.editar', $curso) }}">Editar</a>
                <form method="post" action="{{ route('admin.cursos.eliminar', $curso) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar</button>
                </form>
            </article>
        @endforeach
    </div>
@endsection
