@extends('layouts.publico')
@section('titulo', 'Cursos · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto max-w-6xl px-4 py-12">
        <h1 class="text-4xl text-marca">Catálogo de cursos</h1>
        <p class="mt-2">Precios en soles, incluyen IGV. Los cursos sin precio publicado se consultan por WhatsApp y no se compran en línea.</p>
        <div class="fuente-ui mt-6 flex flex-wrap gap-2">
            <a class="rounded-full px-4 py-2 {{ $categoriaActiva === '' ? 'bg-marca text-white' : 'bg-marca/10' }}" href="{{ route('cursos.index') }}">Todos</a>
            @foreach ($categorias as $categoria)
                <a class="rounded-full px-4 py-2 {{ $categoriaActiva === $categoria->slug ? 'bg-marca text-white' : 'bg-marca/10' }}" href="{{ route('cursos.index', ['categoria' => $categoria->slug]) }}">{{ $categoria->nombre }}</a>
            @endforeach
        </div>
        <div class="mt-8 grid gap-4 md:grid-cols-3">
            @forelse ($cursos as $curso)
                @include('partials.curso-card', ['curso' => $curso])
            @empty
                <p>No hay cursos en este filtro.</p>
            @endforelse
        </div>
    </section>
@endsection
