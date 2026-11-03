@extends('layouts.publico')
@section('titulo', $pagina->titulo.' · '.$marca->nombre_comercial)

@section('contenido')
    <article class="prosa-c3 mx-auto max-w-3xl px-4 py-12">
        <h1 class="text-4xl text-marca">{{ $pagina->titulo }}</h1>
        <div class="mt-6">{!! $pagina->html() !!}</div>
    </article>
@endsection
