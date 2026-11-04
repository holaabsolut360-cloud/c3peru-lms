@props(['ultimaActividad' => null])

@if ($ultimaActividad && $ultimaActividad->curso && $ultimaActividad->leccion)
    <section class="my-5 overflow-hidden rounded-2xl bg-gradient-to-r from-marca to-marca-2 p-6 text-white shadow-lg sm:p-8" aria-labelledby="continuar-aprendiendo-titulo">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-white/90">
                    <i class="ti ti-history" aria-hidden="true"></i> Tu progreso reciente
                </p>
                <h2 id="continuar-aprendiendo-titulo" class="mt-3 text-2xl font-bold text-white">Continúa donde lo dejaste</h2>
                <p class="mt-2 text-white/85">{{ $ultimaActividad->curso->titulo }}</p>
                <p class="mt-1 text-lg font-semibold text-white">{{ $ultimaActividad->leccion->titulo }}</p>
                @if ($ultimaActividad->duracion_segundos)
                    @php
                        $minutosVideo = intdiv($ultimaActividad->duracion_segundos, 60);
                        $segundosVideo = $ultimaActividad->duracion_segundos % 60;
                    @endphp
                    <p class="mt-2 text-sm text-white/75">Video: {{ $minutosVideo }}:{{ str_pad((string) $segundosVideo, 2, '0', STR_PAD_LEFT) }}</p>
                @endif
                <p class="mt-2 text-xs text-white/65">{{ $ultimaActividad->updated_at->diffForHumans() }}</p>
            </div>
            <a class="fuente-ui inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-6 py-3 font-bold text-marca shadow hover:bg-slate-100" href="{{ route('aula.curso', $ultimaActividad->curso) }}#leccion-{{ $ultimaActividad->leccion_id }}">
                <i class="ti ti-player-play-filled" aria-hidden="true"></i> Continuar
            </a>
        </div>
    </section>
@endif
