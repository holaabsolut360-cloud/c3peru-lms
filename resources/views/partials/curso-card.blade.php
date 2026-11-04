<article class="tarjeta-suave flex h-full flex-col overflow-hidden text-left text-fondo">
    <div class="relative m-3 h-44 overflow-hidden rounded-2xl">
        <img src="{{ $curso->imagenPortada() }}" alt="{{ $curso->titulo }}" class="h-full w-full object-cover">
        <span class="fuente-ui absolute left-3 top-3 rounded-full bg-marca px-3 py-1 text-[11px] font-semibold uppercase text-white">{{ $curso->categoria->nombre }}</span>
    </div>
    <div class="flex flex-1 flex-col px-4 pb-4">
        <h3 class="text-lg font-extrabold leading-snug text-marca">
            <a class="text-marca" href="{{ route('cursos.ver', $curso) }}">{{ $curso->titulo }}</a>
        </h3>
        <ul class="mt-3 space-y-1 text-sm text-fondo/80">
            @if ($curso->modalidad)
                <li class="flex items-center gap-2"><i class="ti ti-school text-acento" aria-hidden="true"></i> {{ $curso->modalidad }}</li>
            @endif
            @if ($curso->duracion)
                <li class="flex items-center gap-2"><i class="ti ti-clock text-acento" aria-hidden="true"></i> {{ $curso->duracion }}</li>
            @endif
        </ul>
        <div class="mt-auto flex items-center justify-between gap-3 pt-4">
            <p class="text-lg font-extrabold text-marca">{{ $curso->sePuedeComprar() ? $curso->precioFormateado() : 'Consultar' }}</p>
            <a class="fuente-ui shrink-0 rounded-full bg-marca-2 px-4 py-2 text-sm font-semibold text-white hover:bg-marca" href="{{ route('cursos.ver', $curso) }}">Ver curso</a>
        </div>
    </div>
</article>
