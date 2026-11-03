<article class="flex h-full flex-col rounded-2xl border border-marca/10 bg-white p-5 shadow-sm">
    <p class="fuente-ui text-xs font-semibold uppercase tracking-wide text-marca-2">{{ $curso->categoria->nombre }}</p>
    <h3 class="mt-2 text-xl text-marca">{{ $curso->titulo }}</h3>
    @if ($curso->nivel)
        <p class="mt-1 text-sm text-marca-2">{{ $curso->nivel }}</p>
    @endif
    <ul class="mt-4 space-y-1 text-sm text-fondo/80">
        @if ($curso->duracion)
            <li>Duración: {{ $curso->duracion }}</li>
        @endif
        @if ($curso->modalidad)
            <li>Modalidad: {{ $curso->modalidad }}</li>
        @endif
        @if ($curso->fechas)
            <li>Fechas: {{ $curso->fechas }}</li>
        @endif
        @if ($curso->certificacion)
            <li>{{ $curso->certificacion }}</li>
        @endif
    </ul>
    <p class="mt-4 text-lg font-semibold text-marca">{{ $curso->precioFormateado() }}</p>
    @if ($curso->sePuedeComprar() && $curso->incluye_igv)
        <p class="text-xs text-fondo/70">Incluye IGV</p>
    @endif
    <div class="mt-auto pt-4">
        <a class="fuente-ui text-sm font-semibold text-marca-2" href="{{ route('cursos.ver', $curso) }}">Ver curso</a>
    </div>
</article>
