@props(['title' => 'Contenido de la lección'])

<article {{ $attributes->class(['focus-player rounded-2xl border border-slate-200 bg-white p-5']) }} data-focus-player>
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-3">
        <h2 class="text-xl font-bold text-fondo">{{ $title }}</h2>
        <button
            class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-marca px-3 py-2 text-sm font-semibold text-marca hover:bg-slate-50"
            type="button"
            data-focus-toggle
            aria-pressed="false"
            aria-label="Activar modo enfoque"
        >
            <i class="ti ti-maximize" aria-hidden="true" data-focus-icon></i>
            <span data-focus-label>Modo enfoque</span>
        </button>
    </div>
    {{ $slot }}
</article>
