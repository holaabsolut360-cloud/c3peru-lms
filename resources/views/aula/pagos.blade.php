@extends('layouts.panel')
@section('titulo', 'Mis pagos')

@section('contenido')
    <h1 class="text-3xl text-marca">Mis pagos</h1>
    <table class="mt-6 w-full text-left text-sm">
        <thead><tr class="border-b"><th class="py-2">Curso</th><th>Monto</th><th>Estado</th><th>Comprobante</th></tr></thead>
        <tbody>
            @foreach ($pagos as $pago)
                <tr class="border-b">
                    <td class="py-2">{{ $pago->curso->titulo }}</td>
                    <td>S/ {{ number_format((float) $pago->monto, 2, '.', ',') }}</td>
                    <td>{{ ucfirst($pago->estado) }}</td>
                    <td><a href="{{ route('comprobantes.descargar', $pago) }}">Descargar</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
