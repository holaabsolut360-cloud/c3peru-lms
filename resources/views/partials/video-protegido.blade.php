<figure class="relative mt-5 overflow-hidden rounded-lg bg-black">
    <video
        class="block max-h-[70vh] w-full"
        controls
        preload="metadata"
        controlslist="nodownload"
        disablepictureinpicture
        aria-label="{{ $video->titulo }}"
        @if (auth()->user()->role === 'alumno')
            data-progreso-url="{{ route('aula.lecciones.progreso-video', [$curso, $leccion], false) }}"
        @endif
        @if ($reanudacionSegundos)
            data-reanudar-segundos="{{ $reanudacionSegundos }}"
        @endif
    >
        <source src="{{ $url }}" type="{{ $video->mime_type }}">
        Tu navegador no admite la reproducción de video.
    </video>
    <figcaption class="pointer-events-none absolute bottom-12 right-4 rounded bg-black/45 px-2 py-1 text-xs text-white/65">
        {{ auth()->user()->name }}
    </figcaption>
    <p class="px-3 py-2 text-xs text-white/60">{{ $video->titulo }} · Acceso temporal para alumnos matriculados</p>
</figure>
