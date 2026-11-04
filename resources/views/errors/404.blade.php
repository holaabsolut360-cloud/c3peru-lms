@extends('layouts.publico')
@section('titulo', 'Página no encontrada · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto max-w-xl px-4 py-20 text-center">
        <p class="fuente-ui text-sm font-semibold uppercase tracking-wide text-marca-2">Error 404</p>
        <h1 class="mt-2 text-4xl text-marca">No encontramos esta página</h1>
        <p class="mt-4">El enlace no existe o cambió. Puedes volver al inicio, ver los cursos o escribirnos.</p>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a class="rounded-lg bg-marca px-5 py-3 font-semibold text-white" href="{{ route('inicio') }}">Ir al inicio</a>
            <a class="rounded-lg bg-acento px-5 py-3 font-semibold text-fondo" href="{{ route('cursos.index') }}">Ver cursos</a>
        </div>
    </section>
@endsection
