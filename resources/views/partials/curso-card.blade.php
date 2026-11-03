<article class="flex h-full flex-col overflow-hidden rounded-2xl bg-white text-fondo shadow-lg">
    <div class="relative h-40">
        <img src="{{ $curso->imagenPortada() }}" alt="" class="h-full w-full object-cover">
        <span class="fuente-ui absolute left-3 top-3 rounded-md bg-marca px-2 py-1 text-[11px] font-semibold uppercase text-white">{{ $curso->categoria->nombre }}</span>
    </div>
    <div class="flex flex-1 flex-col p-4">
        <p class="text-xs text-marca-2">{{ $marca->nombre_comercial }}</p>
        <h3 class="mt-1 text-lg leading-snug text-marca">
            <a class="text-marca" href="{{ route('cursos.ver', $curso) }}">{{ $curso->titulo }}</a>
        </h3>
        @if ($curso->nivel)
            <p class="text-sm font-medium text-marca-2">{{ $curso->nivel }}</p>
        @endif
        <ul class="mt-3 space-y-1 text-sm text-fondo/80">
            @if ($curso->duracion)<li>{{ $curso->duracion }}</li>@endif
            @if ($curso->modalidad)<li>{{ $curso->modalidad }}</li>@endif
            @if ($curso->fechas)<li>{{ $curso->fechas }}</li>@endif
            @if ($curso->certificacion)<li>{{ $curso->certificacion }}</li>@endif
        </ul>
        <div class="mt-auto flex items-end justify-between gap-3 pt-4">
            <div>
                <p class="text-lg font-semibold text-marca">{{ $curso->precioFormateado() }}</p>
                @if ($curso->sePuedeComprar() && $curso->incluye_igv)
                    <p class="text-xs text-fondo/60">incl. IGV</p>
                @endif
            </div>
            @if ($curso->sePuedeComprar())
                <a class="fuente-ui shrink-0 rounded-lg bg-acento px-3 py-2 text-sm font-semibold text-fondo" href="{{ route('cursos.ver', $curso) }}">Inscribirme</a>
            @else
                <a class="fuente-ui shrink-0 rounded-lg bg-marca px-3 py-2 text-sm font-semibold text-white" href="{{ route('cursos.ver', $curso) }}">Consultar</a>
            @endif
        </div>
    </div>
</article>
