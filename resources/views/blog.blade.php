@extends('layouts.publico')
@section('titulo', 'Blog · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto max-w-3xl px-4 py-12">
        <h1 class="text-4xl text-marca">Blog</h1>
        <p class="mt-4">Aún no hay publicaciones. Los artículos se sumarán cuando el equipo de contenidos los confirme.</p>
    </section>
@endsection
