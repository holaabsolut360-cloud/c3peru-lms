@extends('layouts.panel')
@section('titulo', $curso->titulo)

@section('contenido')
    <h1 class="text-3xl text-marca">{{ $curso->titulo }}</h1>
    @if ($lecciones->contains(fn ($leccion) => $leccion->materiales->isNotEmpty()))
        <a class="fuente-ui mt-4 inline-flex items-center gap-2 rounded-lg border border-marca px-4 py-2.5 text-sm font-semibold text-marca hover:bg-slate-50" href="{{ route('aula.recursos.index', $curso) }}">
            <i class="ti ti-folder-down" aria-hidden="true"></i> Biblioteca de recursos
        </a>
    @endif
    @include('partials.proximas-clases-widget', ['clases' => $clases])
    @if ($examenes->isNotEmpty())
        <section class="my-6 rounded-xl border border-slate-200 bg-white p-5">
            <h2 class="text-xl font-bold text-fondo">Exámenes disponibles</h2>
            <ul class="mt-4 divide-y divide-slate-100">
                @foreach ($examenes as $examen)
                    <li class="flex flex-wrap items-center justify-between gap-3 py-3">
                        <div>
                            <h3 class="font-semibold text-fondo">{{ $examen->titulo }}</h3>
                            <p class="text-sm text-fondo/65">{{ $examen->preguntas_count }} preguntas · {{ $examen->duracion_minutos }} minutos · {{ $examen->intentos_permitidos }} intentos</p>
                        </div>
                        <a class="fuente-ui inline-flex items-center gap-2 rounded-lg bg-marca px-4 py-2.5 text-sm font-semibold text-white hover:bg-marca-2" href="{{ route('aula.examenes.realizar', $examen) }}">
                            <i class="ti ti-file-check" aria-hidden="true"></i> Rendir examen
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
    @if ($anuncios->isNotEmpty())
        <section class="my-6 rounded-xl border border-amber-200 bg-amber-50 p-5" aria-labelledby="anuncios-curso">
            <h2 id="anuncios-curso" class="text-xl font-bold text-fondo">Anuncios del curso</h2>
            <div class="mt-4 space-y-3">
                @foreach ($anuncios as $anuncio)
                    <article class="rounded-xl border border-amber-200 bg-white p-4">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <h3 class="font-semibold text-fondo">{{ $anuncio->titulo }}</h3>
                            <time class="text-xs text-fondo/60" datetime="{{ $anuncio->publicado_en->toIso8601String() }}">{{ $anuncio->publicado_en->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</time>
                        </div>
                        <p class="mt-2 whitespace-pre-line text-sm text-fondo/80">{{ $anuncio->contenido }}</p>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
    @if ($tareas->isNotEmpty())
        <section class="my-6 rounded-xl border border-slate-200 bg-white p-5" aria-labelledby="tareas-curso">
            <h2 id="tareas-curso" class="text-xl font-bold text-fondo">Tareas y entregas</h2>
            <div class="mt-4 space-y-4">
                @foreach ($tareas as $tarea)
                    @php $ultimaEntrega = $tarea->entregas->first(); @endphp
                    <article class="rounded-xl border border-slate-200 p-4">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <h3 class="font-semibold text-fondo">{{ $tarea->titulo }}</h3>
                            @if ($tarea->fecha_entrega)
                                <span class="text-xs text-fondo/65">Fecha límite: {{ $tarea->fecha_entrega->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</span>
                            @endif
                        </div>
                        <p class="mt-2 whitespace-pre-line text-sm text-fondo/80">{{ $tarea->instrucciones }}</p>
                        <div class="mt-3 flex flex-wrap gap-2 text-xs text-fondo/70">
                            @foreach ($tarea->rubricas as $rubrica)
                                <span class="rounded-full bg-slate-100 px-3 py-1">{{ $rubrica->criterio }} · {{ $rubrica->puntaje_maximo }} pts</span>
                            @endforeach
                        </div>

                        @if ($ultimaEntrega)
                            <div class="mt-4 rounded-xl bg-slate-50 p-3 text-sm">
                                <p class="font-semibold text-fondo">Última entrega: {{ $ultimaEntrega->entregado_en->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</p>
                                @if ($ultimaEntrega->archivo_path)
                                    <a class="mt-1 inline-block text-marca underline" href="{{ route('tareas.entregas.descargar', $ultimaEntrega) }}">Descargar mi archivo</a>
                                @endif
                                @if ($ultimaEntrega->calificada_en)
                                    <p class="mt-2 font-semibold text-emerald-800">Calificación: {{ $ultimaEntrega->puntaje_total }} / {{ $tarea->rubricas->sum('puntaje_maximo') }} puntos</p>
                                    @if ($ultimaEntrega->comentario_docente)<p class="mt-1 whitespace-pre-line">{{ $ultimaEntrega->comentario_docente }}</p>@endif
                                    @foreach ($ultimaEntrega->calificaciones as $calificacion)
                                        <p class="mt-1 text-xs">{{ $calificacion->rubrica->criterio }}: {{ $calificacion->puntaje_obtenido }} pts{{ $calificacion->comentario ? ' · '.$calificacion->comentario : '' }}</p>
                                    @endforeach
                                @else
                                    <p class="mt-1 text-xs text-amber-800">Pendiente de calificación.</p>
                                @endif
                            </div>
                        @endif

                        @if (! $tarea->fecha_entrega || $tarea->fecha_entrega->isFuture())
                            <form method="post" action="{{ route('tareas.entregas.guardar', $tarea) }}" enctype="multipart/form-data" class="mt-4 grid gap-3 sm:grid-cols-2">
                                @csrf
                                <label class="text-xs font-medium text-fondo">Archivo (opcional)
                                    <input name="archivo" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip,.mp4" class="mt-1 block w-full rounded-lg border border-slate-300 px-3 py-2 text-xs">
                                </label>
                                <label class="text-xs font-medium text-fondo">Respuesta o comentario
                                    <textarea name="comentario" rows="2" maxlength="4000" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('comentario') }}</textarea>
                                </label>
                                <p class="text-xs text-fondo/60 sm:col-span-2">Puedes adjuntar un archivo de hasta 20 MB o responder con texto.</p>
                                <button class="justify-self-start rounded-lg bg-marca px-4 py-2 text-sm font-semibold text-white hover:bg-marca-2 sm:col-span-2" type="submit">Enviar entrega</button>
                            </form>
                        @else
                            <p class="mt-3 text-sm font-semibold text-rose-700">El plazo de entrega terminó.</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
    @endif
    <div class="mt-6 space-y-6">
        @foreach ($lecciones as $leccion)
            <x-focus-mode-player :title="$leccion->titulo" id="leccion-{{ $leccion->id }}">
                <div class="mt-3 whitespace-pre-line">{{ $leccion->contenido }}</div>
                <div class="mt-4">
                    @if ($leccionesCompletadas->contains($leccion->id))
                        <p class="text-sm font-semibold text-emerald-800"><i class="ti ti-circle-check" aria-hidden="true"></i> Lección completada</p>
                    @else
                        <form method="post" action="{{ route('aula.lecciones.completar', [$curso, $leccion]) }}">
                            @csrf
                            <button class="fuente-ui inline-flex items-center gap-2 rounded-lg border border-marca px-4 py-2 text-sm font-semibold text-marca hover:bg-slate-50" type="submit">
                                <i class="ti ti-check" aria-hidden="true"></i> Marcar como completada
                            </button>
                        </form>
                    @endif
                </div>
                @foreach ($videosPorLeccion->get($leccion->id, collect()) as $entrada)
                    @include('partials.video-protegido', [
                        'video' => $entrada['video'],
                        'url' => $entrada['url'],
                        'curso' => $curso,
                        'leccion' => $leccion,
                        'reanudacionSegundos' => $progresoVideoReciente?->leccion_id === $leccion->id ? $progresoVideoReciente->duracion_segundos : null,
                    ])
                @endforeach
                @if ($leccion->materiales->isNotEmpty())
                    <div class="mt-5 rounded-xl bg-slate-50 p-4">
                        <h3 class="font-semibold text-fondo">Material complementario</h3>
                        <ul class="mt-2 space-y-2">
                            @foreach ($leccion->materiales as $material)
                                <li>
                                    <a class="inline-flex items-center gap-2 text-sm font-medium text-marca underline" href="{{ route('aula.materiales.descargar', $material) }}">
                                        <i class="ti ti-download" aria-hidden="true"></i> {{ $material->nombre_original }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </x-focus-mode-player>
        @endforeach
    </div>
@endsection
