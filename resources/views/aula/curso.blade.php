@extends('layouts.panel')
@section('titulo', $curso->titulo)

@section('contenido')
    <h1 class="text-3xl text-marca">{{ $curso->titulo }}</h1>
    <div class="mt-6 space-y-6">
        @foreach ($lecciones as $leccion)
            <article class="rounded-2xl border p-5">
                <h2 class="text-xl">{{ $leccion->titulo }}</h2>
                <div class="mt-3 whitespace-pre-line">{{ $leccion->contenido }}</div>
            </article>
        @endforeach
    </div>
@endsection
