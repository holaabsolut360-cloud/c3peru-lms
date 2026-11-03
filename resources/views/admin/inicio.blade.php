@extends('layouts.panel')
@section('titulo', 'Administración')

@section('contenido')
    <h1 class="text-3xl text-marca">Administración</h1>
    <div class="mt-6 grid gap-4 md:grid-cols-4">
        <article class="rounded-2xl bg-fondo p-4 text-white"><p class="text-3xl">{{ $pagosPendientes }}</p><p>Pagos pendientes</p></article>
        <article class="rounded-2xl bg-marca p-4 text-white"><p class="text-3xl">{{ $alumnos }}</p><p>Alumnos</p></article>
        <article class="rounded-2xl bg-marca-2 p-4 text-white"><p class="text-3xl">{{ $certificados }}</p><p>Certificados</p></article>
        <article class="rounded-2xl bg-fondo-alt p-4 text-white"><p class="text-3xl">{{ $reclamos }}</p><p>Reclamaciones</p></article>
    </div>
@endsection
