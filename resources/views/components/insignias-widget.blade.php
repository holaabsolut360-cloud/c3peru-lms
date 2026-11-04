@props(['insignias'])

<section class="mb-8 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6" aria-labelledby="mis-insignias">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <h2 id="mis-insignias" class="flex items-center gap-2 text-xl font-bold text-fondo">
                <i class="ti ti-medal text-2xl text-amber-500" aria-hidden="true"></i>
                Mis insignias digitales
            </h2>
            <p class="mt-1 text-sm text-fondo/65">Logros verificables que puedes compartir en tu perfil profesional.</p>
        </div>
        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">{{ $insignias->count() }} obtenidas</span>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($insignias as $otorgamiento)
            <article class="rounded-xl border border-slate-200 bg-gradient-to-br from-amber-50/70 to-white p-4">
                <div class="mb-4 flex items-center gap-3">
                    <img class="h-14 w-14 rounded-lg border border-slate-200 bg-white object-contain p-1" src="{{ asset(ltrim($otorgamiento->insignia->imagen_url, '/')) }}" alt="Insignia: {{ $otorgamiento->insignia->nombre }}" width="56" height="56">
                    <div class="min-w-0">
                        <h3 class="truncate font-semibold text-fondo">{{ $otorgamiento->insignia->nombre }}</h3>
                        <p class="text-xs text-fondo/60">Otorgada {{ $otorgamiento->otorgada_en->timezone(config('app.timezone'))->format('d/m/Y') }}</p>
                    </div>
                </div>
                <p class="mb-4 line-clamp-3 text-sm text-fondo/70">{{ $otorgamiento->insignia->descripcion }}</p>
                <div class="flex gap-2">
                    <a class="inline-flex flex-1 items-center justify-center gap-1 rounded-lg bg-[#0a66c2] px-3 py-2 text-xs font-semibold text-white hover:bg-[#004182]" href="{{ route('badges.linkedin', $otorgamiento) }}" target="_blank" rel="noopener">
                        <i class="ti ti-brand-linkedin" aria-hidden="true"></i> Agregar a LinkedIn
                    </a>
                    <a class="inline-flex items-center justify-center rounded-lg bg-slate-100 px-3 py-2 text-xs font-semibold text-fondo hover:bg-slate-200" href="{{ $otorgamiento->assertion_url }}" target="_blank" rel="noopener" aria-label="Verificar insignia {{ $otorgamiento->insignia->nombre }}">
                        <i class="ti ti-shield-check" aria-hidden="true"></i>
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-8 text-center">
                <i class="ti ti-medal mb-2 text-4xl text-slate-300" aria-hidden="true"></i>
                <p class="text-sm text-fondo/65">Aprueba un examen de tu curso con la nota mínima para obtener una insignia digital.</p>
            </div>
        @endforelse
    </div>
</section>
