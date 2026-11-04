@extends('layouts.panel')
@section('titulo', 'Notificaciones')

@section('contenido')
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl text-marca">Notificaciones</h1>
            <p class="mt-1 text-sm text-fondo/65">{{ $noLeidas }} sin leer</p>
        </div>
        @if ($noLeidas > 0)
            <form action="{{ route('notificaciones.todas') }}" method="post">
                @csrf
                <button class="fuente-ui rounded-lg border border-marca px-4 py-2 text-sm font-semibold text-marca hover:bg-slate-50" type="submit">Marcar todas como leídas</button>
            </form>
        @endif
    </div>

    <div class="mt-5 divide-y divide-slate-100 rounded-xl border border-slate-200 bg-white">
        @forelse ($notificaciones as $notificacion)
            @php
                $destino = $notificacion->data['url'] ?? null;
                $destino = is_string($destino) && str_starts_with($destino, '/') && ! str_starts_with($destino, '//') && ! str_contains($destino, '\\')
                    ? $destino
                    : null;
            @endphp
            <article class="flex flex-wrap items-start justify-between gap-3 p-4 {{ $notificacion->read_at ? '' : 'bg-blue-50/50' }}">
                <div class="min-w-0 flex-1">
                    @if ($destino)
                        <a class="font-semibold text-fondo hover:text-marca hover:underline" href="{{ route('notificaciones.abrir', $notificacion->id) }}">{{ $notificacion->data['titulo'] ?? 'Nueva notificación' }}</a>
                    @else
                        <h2 class="font-semibold text-fondo">{{ $notificacion->data['titulo'] ?? 'Nueva notificación' }}</h2>
                    @endif
                    <p class="mt-1 whitespace-pre-line text-sm text-fondo/70">{{ $notificacion->data['mensaje'] ?? '' }}</p>
                    <time class="mt-2 block text-xs text-fondo/50" datetime="{{ $notificacion->created_at->toIso8601String() }}">{{ $notificacion->created_at->diffForHumans() }}</time>
                </div>
                @unless ($notificacion->read_at)
                    <form action="{{ route('notificaciones.leida', $notificacion->id) }}" method="post">
                        @csrf
                        <button class="text-sm font-semibold text-marca underline" type="submit">Marcar como leída</button>
                    </form>
                @endunless
            </article>
        @empty
            <div class="p-10 text-center">
                <i class="ti ti-bell-off text-3xl text-slate-300" aria-hidden="true"></i>
                <p class="mt-2 text-sm text-fondo/65">Todavía no tienes notificaciones.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-5">{{ $notificaciones->links() }}</div>
@endsection
