<section class="my-6 rounded-xl border border-slate-200 bg-white p-5 sm:p-6" aria-labelledby="clases-en-vivo-titulo">
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
        <h2 id="clases-en-vivo-titulo" class="flex items-center gap-2 text-lg font-bold text-fondo">
            <i class="ti ti-video text-xl text-marca" aria-hidden="true"></i> Próximas clases en vivo
        </h2>
        <span class="fuente-ui rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-fondo/70">{{ $clases->count() }} programadas</span>
    </div>

    <div class="mt-4 space-y-3">
        @forelse ($clases as $clase)
            @php
                $estadoClase = $clase->estadoActual();
                $estiloEstado = match ($estadoClase) {
                    'en_vivo' => 'bg-red-50 text-red-800',
                    'finalizada' => 'bg-slate-100 text-slate-600',
                    default => 'bg-blue-50 text-blue-800',
                };
                $textoEstado = match ($estadoClase) {
                    'en_vivo' => 'En vivo',
                    'finalizada' => 'Finalizada',
                    default => 'Programada',
                };
            @endphp
            <article class="flex flex-col gap-4 rounded-lg border border-slate-200 bg-slate-50 p-4 sm:flex-row sm:items-center">
                <div class="flex shrink-0 items-center gap-3 sm:w-44">
                    <div class="flex h-14 w-14 flex-col items-center justify-center rounded-lg border border-slate-200 bg-white">
                        <span class="fuente-ui text-xs font-bold uppercase text-marca">{{ $clase->fecha_inicio->timezone(config('app.timezone'))->translatedFormat('M') }}</span>
                        <span class="text-xl font-bold leading-none text-fondo">{{ $clase->fecha_inicio->timezone(config('app.timezone'))->format('d') }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-fondo">{{ $clase->fecha_inicio->timezone(config('app.timezone'))->format('H:i') }}</p>
                        <p class="text-xs text-fondo/60">{{ $clase->fecha_inicio->timezone(config('app.timezone'))->translatedFormat('D, d M') }}</p>
                    </div>
                </div>

                <div class="min-w-0 flex-1">
                    <h3 class="font-semibold text-fondo">{{ $clase->titulo }}</h3>
                    <p class="mt-1 text-sm text-fondo/65">{{ $clase->curso->titulo }}</p>
                    @if ($clase->leccion)
                        <p class="mt-1 text-sm text-fondo/65">{{ $clase->leccion->titulo }}</p>
                    @endif
                    <p class="mt-1 flex items-center gap-1.5 text-xs text-fondo/60"><i class="ti ti-user" aria-hidden="true"></i>{{ $clase->docente->name }}</p>
                    <span class="fuente-ui mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $estiloEstado }}">{{ $textoEstado }}</span>
                </div>

                <div class="flex shrink-0 flex-wrap items-center gap-2 sm:w-56 sm:justify-end">
                    @if ($clase->esEnlaceSimulado())
                        <span class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-sky-200 bg-sky-50 px-4 py-2.5 text-sm font-semibold text-sky-900" title="Enlace de demostración; no abre una reunión real">
                            <i class="ti ti-brand-teams" aria-hidden="true"></i> Teams simulado
                        </span>
                    @elseif ($clase->teams_join_url)
                        <a class="fuente-ui inline-flex w-full items-center justify-center gap-2 rounded-lg bg-marca px-4 py-2.5 text-sm font-semibold text-white hover:bg-marca-2 sm:w-auto" href="{{ $clase->teams_join_url }}" target="_blank" rel="noopener noreferrer">
                            <i class="ti ti-brand-teams" aria-hidden="true"></i> Unirse a Teams
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 text-sm text-fondo/60"><i class="ti ti-clock" aria-hidden="true"></i> Enlace pendiente</span>
                    @endif
                    @if (in_array(auth()->user()->role, ['alumno', 'docente'], true))
                        <a class="fuente-ui inline-flex items-center justify-center gap-2 rounded-lg border border-marca px-3 py-2.5 text-sm font-semibold text-marca hover:bg-slate-100" href="{{ route('calendario.descargar', $clase) }}" aria-label="Agregar {{ $clase->titulo }} al calendario">
                            <i class="ti ti-calendar-plus" aria-hidden="true"></i> Agendar
                        </a>
                    @endif
                </div>
            </article>
        @empty
            <div class="rounded-lg border border-dashed border-slate-300 px-4 py-8 text-center">
                <i class="ti ti-calendar-off text-3xl text-slate-400" aria-hidden="true"></i>
                <p class="mt-2 text-sm text-fondo/65">No hay clases en vivo programadas próximamente.</p>
            </div>
        @endforelse
    </div>
</section>
