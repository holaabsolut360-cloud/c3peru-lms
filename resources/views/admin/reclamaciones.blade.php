@extends('layouts.panel')
@section('titulo', 'Reclamaciones')

@section('contenido')
    <h1 class="text-3xl text-marca">Libro de reclamaciones</h1>
    <p class="mt-2 text-sm">Este listado no se consulta desde la verificación de certificados.</p>
    <div class="mt-6 space-y-3">
        @forelse ($reclamaciones as $reclamo)
            <article class="rounded-2xl border p-4 text-sm">
                <p class="font-semibold">{{ $reclamo->codigo }} · {{ ucfirst($reclamo->tipo) }}</p>
                <p>{{ $reclamo->nombre }} {{ $reclamo->primer_apellido }} · {{ $reclamo->correo }}</p>
                <p>{{ $reclamo->detalle }}</p>
                <p>Pedido: {{ $reclamo->pedido_cliente }}</p>
            </article>
        @empty
            <p>No hay hojas registradas.</p>
        @endforelse
    </div>
@endsection
