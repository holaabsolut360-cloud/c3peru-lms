@php
    $usuario = auth()->user();
    $notificaciones = $usuario->notifications()->latest()->limit(8)->get();
    $noLeidas = $usuario->unreadNotifications()->count();
    $menuId = 'notificaciones-menu-'.\Illuminate\Support\Str::uuid();
@endphp

<div class="relative" data-notifications-dropdown>
    <button class="relative inline-flex items-center justify-center rounded-lg p-2 text-white hover:bg-white/10" type="button" data-notifications-toggle aria-expanded="false" aria-controls="{{ $menuId }}" aria-label="Notificaciones{{ $noLeidas > 0 ? ', '.$noLeidas.' sin leer' : '' }}">
        <i class="ti ti-bell text-xl" aria-hidden="true"></i>
        @if ($noLeidas > 0)
            <span class="absolute right-0.5 top-0.5 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">{{ $noLeidas > 9 ? '9+' : $noLeidas }}</span>
        @endif
    </button>

    <section id="{{ $menuId }}" class="absolute right-0 z-50 mt-2 w-[min(24rem,calc(100vw-2rem))] overflow-hidden rounded-2xl border border-slate-200 bg-white text-fondo shadow-2xl" data-notifications-menu aria-label="Notificaciones recientes" hidden>
        <div class="flex items-center justify-between gap-3 border-b border-slate-100 p-4">
            <h2 class="font-bold">Notificaciones</h2>
            @if ($noLeidas > 0)
                <form action="{{ route('notificaciones.todas') }}" method="post">
                    @csrf
                    <button class="text-xs font-semibold text-marca hover:underline" type="submit">Marcar todas como leídas</button>
                </form>
            @endif
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse ($notificaciones as $notificacion)
                @php
                    $destino = $notificacion->data['url'] ?? null;
                    $destino = is_string($destino) && str_starts_with($destino, '/') && ! str_starts_with($destino, '//') && ! str_contains($destino, '\\')
                        ? $destino
                        : null;
                    $categoria = $notificacion->data['categoria'] ?? '';
                    $icono = match ($categoria) {
                        'pago' => 'ti-wallet',
                        'clase' => 'ti-video',
                        'anuncio' => 'ti-speakerphone',
                        default => 'ti-bell',
                    };
                @endphp
                <article class="border-b border-slate-100 p-4 {{ $notificacion->read_at ? '' : 'bg-blue-50/60' }}">
                    <div class="flex items-start gap-3">
                        <i class="ti {{ $icono }} mt-0.5 text-lg text-marca" aria-hidden="true"></i>
                        <div class="min-w-0 flex-1">
                            @if ($destino)
                                <a class="text-sm font-semibold text-fondo hover:text-marca hover:underline" href="{{ route('notificaciones.abrir', $notificacion->id) }}">{{ $notificacion->data['titulo'] ?? 'Nueva notificación' }}</a>
                            @else
                                <p class="text-sm font-semibold text-fondo">{{ $notificacion->data['titulo'] ?? 'Nueva notificación' }}</p>
                            @endif
                            <p class="mt-1 line-clamp-2 text-xs text-fondo/70">{{ $notificacion->data['mensaje'] ?? '' }}</p>
                            <p class="mt-1 text-xs text-fondo/50">{{ $notificacion->created_at->diffForHumans() }}</p>
                            @unless ($notificacion->read_at)
                                <form class="mt-2" action="{{ route('notificaciones.leida', $notificacion->id) }}" method="post">
                                    @csrf
                                    <button class="text-xs font-semibold text-marca underline" type="submit">Marcar como leída</button>
                                </form>
                            @endunless
                        </div>
                    </div>
                </article>
            @empty
                <div class="p-8 text-center">
                    <i class="ti ti-bell-off text-3xl text-slate-300" aria-hidden="true"></i>
                    <p class="mt-2 text-sm text-fondo/65">No tienes notificaciones.</p>
                </div>
            @endforelse
        </div>

        <div class="border-t border-slate-100 p-3 text-center">
            <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('notificaciones.index') }}">Ver todas</a>
        </div>
    </section>
</div>
