@extends('layouts.panel')
@section('titulo', 'Pagos')

@section('contenido')
    <h1 class="text-3xl text-marca">Pagos</h1>
    <p class="mt-2 text-sm">Solo un administrador pasa el control de aprobación.</p>
    <div class="mt-6 space-y-4">
        @foreach ($pagos as $pago)
            <article class="rounded-2xl border p-4">
                <p class="font-semibold">{{ $pago->curso->titulo }} · S/ {{ number_format((float) $pago->monto, 2, '.', ',') }}</p>
                <p class="text-sm">{{ $pago->usuario->name }} · {{ $pago->usuario->email }} · {{ ucfirst($pago->estado) }}</p>
                @if ($pago->referencia)<p class="text-sm">Operación: {{ $pago->referencia }}</p>@endif
                <p class="text-sm"><a href="{{ route('comprobantes.descargar', $pago) }}">Ver comprobante</a></p>
                @if ($pago->estado === 'pendiente')
                    <div class="mt-3 flex flex-wrap items-end gap-3">
                        <form method="post" action="{{ route('admin.pagos.aprobar', $pago) }}">
                            @csrf
                            <button class="rounded-full bg-marca px-4 py-2 text-sm text-white" type="submit">Aprobar y matricular</button>
                        </form>
                        <form method="post" action="{{ route('admin.pagos.rechazar', $pago) }}" class="flex gap-2">
                            @csrf
                            <input name="motivo" required placeholder="Motivo del rechazo" class="rounded-xl border px-3 py-2">
                            <button class="rounded-full border px-4 py-2 text-sm" type="submit">Rechazar</button>
                        </form>
                    </div>
                @endif
            </article>
        @endforeach
    </div>
@endsection
