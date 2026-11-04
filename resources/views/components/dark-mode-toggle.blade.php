<button
    {{ $attributes->class(['inline-flex h-10 w-10 items-center justify-center rounded-lg text-white transition hover:bg-white/10 focus-visible:outline-white']) }}
    type="button"
    data-dark-mode-toggle
    aria-label="Activar modo oscuro"
    aria-pressed="false"
>
    <i class="ti ti-moon text-xl" aria-hidden="true" data-dark-mode-moon></i>
    <i class="ti ti-sun text-xl" aria-hidden="true" data-dark-mode-sun hidden></i>
    <span class="sr-only" data-dark-mode-label>Activar modo oscuro</span>
</button>
