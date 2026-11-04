<div class="mt-6 space-y-5" @if ($examenIniciado) wire:poll.5s="actualizarTiempo" @endif>
    <header class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6">
        <p class="fuente-ui text-xs font-semibold uppercase tracking-wide text-marca">Evaluación</p>
        <h1 class="mt-1 text-2xl font-bold text-fondo">{{ $examen->titulo }}</h1>
        @if ($examen->instrucciones)
            <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-fondo/70">{{ $examen->instrucciones }}</p>
        @endif
        <div class="mt-4 flex flex-wrap gap-4 text-sm text-fondo/65">
            <span class="flex items-center gap-2"><i class="ti ti-clock text-marca" aria-hidden="true"></i>{{ $examen->duracion_minutos }} minutos</span>
            <span class="flex items-center gap-2"><i class="ti ti-target text-marca" aria-hidden="true"></i>Aprobación: {{ $examen->puntaje_aprobatorio }}/20</span>
            <span class="flex items-center gap-2"><i class="ti ti-repeat text-marca" aria-hidden="true"></i>{{ $examen->intentos_permitidos }} intentos</span>
        </div>
    </header>

    @if ($intentosAgotados)
        <section class="rounded-xl border border-slate-200 bg-white p-6" role="status">
            <h2 class="text-xl font-bold text-fondo">Intentos completados</h2>
            <p class="mt-2 text-sm text-fondo/70">Ya utilizaste todos los intentos permitidos para este examen.</p>
            @if ($notaFinal !== null)
                <p class="mt-4 text-2xl font-bold {{ $aprobado ? 'text-emerald-700' : 'text-red-700' }}">{{ number_format($notaFinal, 2) }} / 20 · {{ $aprobado ? 'Aprobado' : 'No aprobado' }}</p>
            @endif
        </section>
    @elseif ($examenFinalizado)
        <section class="rounded-xl border border-slate-200 bg-white p-6 text-center" role="status">
            <i class="ti {{ $aprobado ? 'ti-circle-check text-emerald-600' : 'ti-clipboard-check text-amber-700' }} text-4xl" aria-hidden="true"></i>
            <h2 class="mt-3 text-2xl font-bold {{ $aprobado ? 'text-emerald-700' : 'text-fondo' }}">{{ $aprobado ? 'Examen aprobado' : 'Examen finalizado' }}</h2>
            <p class="mt-2 text-fondo/70">Tu nota es</p>
            <p class="mt-1 text-5xl font-bold text-fondo">{{ number_format((float) $notaFinal, 2) }}<span class="text-2xl text-fondo/55"> / 20</span></p>
            <p class="mt-3 text-sm text-fondo/60">La calificación se calculó y guardó en el sistema.</p>
        </section>
    @elseif (! $examenIniciado)
        <section class="rounded-xl border border-slate-200 bg-white p-6 text-center sm:p-8">
            <i class="ti ti-file-check text-4xl text-marca" aria-hidden="true"></i>
            <h2 class="mt-3 text-xl font-bold text-fondo">¿Listo para comenzar?</h2>
            <p class="mx-auto mt-2 max-w-xl text-sm leading-relaxed text-fondo/70">El tiempo empieza al iniciar. Tus respuestas se guardarán durante el intento; al finalizar, la calificación se calcula automáticamente.</p>
            @error('examen')<p class="mt-3 text-sm font-medium text-red-700">{{ $message }}</p>@enderror
            <button class="fuente-ui mt-6 inline-flex items-center gap-2 rounded-lg bg-marca px-6 py-3 font-semibold text-white hover:bg-marca-2" type="button" wire:click="iniciarExamen">
                <i class="ti ti-player-play" aria-hidden="true"></i> Iniciar examen
            </button>
        </section>
    @else
        <div class="sticky top-3 z-20 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
            <p class="fuente-ui text-sm font-semibold text-fondo">Tiempo restante <span class="ml-2 font-mono text-lg {{ $tiempoRestante <= 300 ? 'text-red-700' : 'text-marca' }}">{{ gmdate('H:i:s', $tiempoRestante) }}</span></p>
            <p class="text-sm text-fondo/65">{{ count($respuestas) }} / {{ count($preguntas) }} respondidas</p>
            <button class="fuente-ui rounded-lg bg-marca px-4 py-2.5 text-sm font-semibold text-white hover:bg-marca-2" type="button" wire:click="finalizarExamen" wire:confirm="¿Finalizar el examen? Ya no podrás cambiar tus respuestas.">
                Finalizar examen
            </button>
        </div>

        @error('respuestas')<p class="rounded-lg bg-red-50 p-3 text-sm text-red-800">{{ $message }}</p>@enderror

        <div class="space-y-4">
            @foreach ($preguntas as $indice => $pregunta)
                <fieldset class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6" wire:key="pregunta-{{ $pregunta['id'] }}">
                    <legend class="w-full">
                        <span class="mr-2 inline-flex h-8 w-8 items-center justify-center rounded-full bg-marca text-sm font-bold text-white">{{ $indice + 1 }}</span>
                        <span class="font-semibold leading-relaxed text-fondo">{{ $pregunta['enunciado'] }}</span>
                        <span class="ml-2 text-xs text-fondo/55">{{ $pregunta['puntaje'] }} pt</span>
                    </legend>
                    <div class="mt-4 grid gap-2 sm:grid-cols-2">
                        @foreach ($pregunta['opciones'] as $letra => $opcion)
                            <label class="flex cursor-pointer items-start gap-3 rounded-lg border p-3 transition-colors {{ ($respuestas[$pregunta['id']] ?? null) === $letra ? 'border-marca bg-marca/5' : 'border-slate-200 hover:border-marca/50' }}">
                                <input type="radio" name="respuesta-{{ $pregunta['id'] }}" value="{{ $letra }}" @checked(($respuestas[$pregunta['id']] ?? null) === $letra) wire:click="responder({{ $pregunta['id'] }}, '{{ $letra }}')" class="mt-1 h-4 w-4 border-slate-300 text-marca focus:ring-marca">
                                <span class="text-sm leading-relaxed text-fondo">{{ $opcion }}</span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            @endforeach
        </div>
    @endif
</div>
