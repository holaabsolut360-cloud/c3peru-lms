@extends('layouts.publico')
@section('titulo', $curso->titulo.' · '.$marca->nombre_comercial)
@section('contexto-whatsapp', 'curso')
@section('descripcion', $curso->titulo.' de '.$curso->categoria->nombre.'. '.$curso->precioFormateado().'. '.$marca->nombre_comercial)

@php
    $palabras = preg_split('/\s+/', mb_strtoupper($curso->categoria->nombre)) ?: [];
    $acento = array_pop($palabras);
    $resto = implode(' ', $palabras);
    $nombreInstructor = $curso->instructor_nombre ?: $curso->docente?->name;
@endphp

@section('contenido')
    <nav class="border-b border-slate-200 bg-white" aria-label="Miga de pan">
        <ol class="mx-auto flex max-w-6xl flex-wrap items-center gap-2 px-4 py-4 text-sm sm:px-6 lg:px-8">
            <li><a class="text-marca hover:text-marca-2" href="{{ route('inicio') }}">Inicio</a></li>
            <li aria-hidden="true"><i class="ti ti-chevron-right text-xs text-fondo/45"></i></li>
            <li><a class="text-marca hover:text-marca-2" href="{{ route('cursos.index') }}">Cursos</a></li>
            <li aria-hidden="true"><i class="ti ti-chevron-right text-xs text-fondo/45"></i></li>
            <li class="font-semibold text-fondo" aria-current="page">{{ $curso->titulo }}</li>
        </ol>
    </nav>

    <article class="bg-white pb-28 text-left text-fondo lg:pb-0">
        <header class="ficha-bloque mx-auto max-w-6xl px-4">
            <div class="relative min-h-[28rem] overflow-hidden rounded-3xl bg-fondo">
                <img src="{{ $curso->imagenPortada() }}" alt="{{ $curso->titulo }}" class="absolute inset-0 h-full w-full object-cover">
                <div class="velo-portada absolute inset-0"></div>
                <div class="relative flex min-h-[28rem] max-w-3xl flex-col justify-end p-6 text-white md:p-10">
                    <p class="fuente-ui text-sm font-semibold uppercase tracking-[0.14em]">
                        {{ $resto }} @if ($acento)<span class="text-acento">{{ $acento }}</span>@endif
                    </p>
                    <h1 class="mt-3 text-4xl font-extrabold leading-tight text-white md:text-5xl">{{ $curso->titulo }}</h1>
                    <p class="mt-4 max-w-2xl text-base text-white/95 md:text-lg">{{ $curso->beneficio ?: 'Al finalizar podrás aplicar lo trabajado en este programa. El detalle de competencias está por confirmar.' }}</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        @if ($curso->sePuedeComprar())
                            <a class="fuente-ui rounded-full bg-marca-2 px-7 py-3 font-semibold text-white hover:bg-marca" href="{{ route('compra.crear', $curso) }}">Inscribirme</a>
                        @else
                            <a class="fuente-ui rounded-full bg-marca-2 px-7 py-3 font-semibold text-white hover:bg-marca" href="#inscripcion">Inscribirme</a>
                        @endif
                        <a class="fuente-ui rounded-full border border-white px-7 py-3 font-semibold text-white" href="{{ $marca->enlaceWhatsapp('Hola, quiero consultar el curso '.$curso->titulo) }}" target="_blank" rel="noopener">Consultar por WhatsApp</a>
                    </div>
                </div>
            </div>
        </header>

        <section class="ficha-bloque fondo-suave" aria-label="Datos clave">
            <div class="mx-auto grid max-w-6xl grid-cols-1 gap-4 px-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ([
                    ['ti-clock', 'Duración', $curso->datoVisible($curso->duracion)],
                    ['ti-calendar', 'Inicio', $curso->inicioVisible()],
                    ['ti-calendar-time', 'Horario', $curso->datoVisible($curso->horario)],
                    ['ti-school', 'Modalidad', $curso->datoVisible($curso->modalidad)],
                    ['ti-certificate', 'Certificación', $curso->datoVisible($curso->certificacion)],
                    ['ti-cash', 'Precio', $curso->sePuedeComprar() ? $curso->precioFormateado() : 'Consultar'],
                ] as [$icono, $etiqueta, $valor])
                    <article class="tarjeta-suave flex items-start gap-3 p-5">
                        <i class="ti {{ $icono }} text-2xl text-acento" aria-hidden="true"></i>
                        <div>
                            <p class="text-sm text-fondo/70">{{ $etiqueta }}</p>
                            <p class="font-semibold text-marca">{{ $valor }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="ficha-bloque bg-white">
            <div class="mx-auto grid max-w-6xl items-start gap-8 px-4 lg:grid-cols-3">
                <div class="space-y-12 lg:col-span-2">
                    @if ($curso->descripcion)
                        <div>
                            <h2 class="text-3xl font-extrabold text-marca">Sobre el curso</h2>
                            <p class="mt-4 whitespace-pre-line text-base leading-7">{{ $curso->descripcion }}</p>
                        </div>
                    @endif

                    @if ($curso->aprendizajesLista() !== [])
                        <div>
                            <h2 class="text-3xl font-extrabold text-marca">Lo que aprenderás</h2>
                            <ul class="mt-4 space-y-3">
                                @foreach ($curso->aprendizajesLista() as $aprendizaje)
                                    <li class="flex items-start gap-3"><i class="ti ti-check text-xl text-acento" aria-hidden="true"></i><span>{{ $aprendizaje }}</span></li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($curso->temarioLista() !== [])
                        <div>
                            <h2 class="text-3xl font-extrabold text-marca">Temario</h2>
                            <div class="mt-4 space-y-3">
                                @foreach ($curso->temarioLista() as $indice => $modulo)
                                    <details class="tarjeta-suave p-4" @if($loop->first) open @endif>
                                        <summary class="flex cursor-pointer list-none items-center gap-3 font-semibold text-marca [&::-webkit-details-marker]:hidden">
                                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-marca text-sm text-white">{{ $indice + 1 }}</span>
                                            {{ $modulo['titulo'] }}
                                        </summary>
                                        @if ($modulo['descripcion'] !== '')
                                            <p class="mt-3 pl-11 text-sm leading-6">{{ $modulo['descripcion'] }}</p>
                                        @endif
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if ($curso->dirigido_a || $curso->requisitos)
                        <div class="grid gap-4 md:grid-cols-2">
                            @if ($curso->dirigido_a)
                                <article class="tarjeta-suave p-5">
                                    <h2 class="text-xl font-extrabold text-marca">A quién va dirigido</h2>
                                    <p class="mt-3 text-sm leading-6">{{ $curso->dirigido_a }}</p>
                                </article>
                            @endif
                            @if ($curso->requisitos)
                                <article class="tarjeta-suave p-5">
                                    <h2 class="text-xl font-extrabold text-marca">Requisitos</h2>
                                    <p class="mt-3 text-sm leading-6">{{ $curso->requisitos }}</p>
                                </article>
                            @endif
                        </div>
                    @endif

                    <div>
                        <h2 class="text-3xl font-extrabold text-marca">Instructor</h2>
                        <article class="tarjeta-suave mt-4 flex items-center gap-4 p-5">
                            @if ($curso->instructor_foto)
                                <img src="{{ $curso->instructor_foto }}" alt="{{ $nombreInstructor ?: 'Instructor del curso' }}" class="h-16 w-16 rounded-full object-cover">
                            @else
                                <span class="flex h-16 w-16 items-center justify-center rounded-full bg-fondo text-sm font-semibold text-white" aria-hidden="true">C3</span>
                            @endif
                            <div>
                                <p class="font-semibold text-marca">{{ $nombreInstructor ?: 'POR CONFIRMAR' }}</p>
                                <p class="text-sm">{{ $curso->instructor_cargo ?: ($curso->docente ? 'Docente C3 Perú' : 'Cargo POR CONFIRMAR') }}</p>
                                @if ($curso->instructor_enlace)
                                    <a class="text-sm font-semibold text-marca-2" href="{{ $curso->instructor_enlace }}">Ver perfil</a>
                                @else
                                    <p class="text-sm text-fondo/70">Enlace POR CONFIRMAR</p>
                                @endif
                            </div>
                        </article>
                    </div>
                </div>

                <aside id="inscripcion" class="solo-laptop lg:sticky lg:top-6">
                    <div class="tarjeta-suave p-6">
                        <p class="text-sm text-fondo/70">Inversión</p>
                        <p class="text-3xl font-extrabold text-marca">{{ $curso->sePuedeComprar() ? $curso->precioFormateado() : 'Consultar' }}</p>
                        @if ($curso->sePuedeComprar() && $curso->incluye_igv)
                            <p class="text-xs text-fondo/60">Incluye IGV. El acceso se habilita al aprobar el comprobante.</p>
                        @endif
                        <p class="mt-4 font-semibold text-marca">Qué incluye</p>
                        <ul class="mt-2 space-y-2 text-sm">
                            @foreach ($curso->incluyeLista() as $item)
                                <li class="flex items-start gap-2"><i class="ti ti-check text-acento" aria-hidden="true"></i> {{ $item }}</li>
                            @endforeach
                        </ul>
                        @if ($curso->sePuedeComprar())
                            <a class="fuente-ui mt-5 block rounded-full bg-marca-2 px-5 py-3 text-center font-semibold text-white hover:bg-marca" href="{{ route('compra.crear', $curso) }}">Inscribirme con comprobante</a>
                        @else
                            <p class="mt-4 text-sm">Este curso no tiene precio publicado y no se puede comprar en línea.</p>
                        @endif
                        @if ($curso->temario_archivo)
                            <a class="mt-3 block text-sm font-semibold text-marca-2" href="{{ $curso->temario_archivo }}">Descargar temario</a>
                        @endif
                        <a class="mt-3 block text-sm font-semibold text-marca-2" href="{{ $marca->enlaceWhatsapp('Hola, quiero consultar el curso '.$curso->titulo) }}" target="_blank" rel="noopener">Escribir por WhatsApp</a>
                    </div>
                </aside>
            </div>
        </section>

        <section class="ficha-bloque fondo-suave">
            <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 md:grid-cols-2">
                <div class="tarjeta-suave border-4 border-marca p-6">
                    <p class="text-xs font-semibold uppercase tracking-wide text-acento">{{ $marca->nombre_comercial }}</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-marca">Certificado de {{ $curso->titulo }}</h2>
                    <p class="mt-3 text-sm">Vista previa del documento que se emite al completar el programa. Lleva el código y la marca de C3.</p>
                </div>
                <div>
                    <h2 class="text-3xl font-extrabold text-marca">Certificación</h2>
                    <p class="mt-3">Verificable por código o DNI.</p>
                    <a class="mt-4 inline-block font-semibold text-marca-2" href="{{ route('certificados.formulario') }}">Verificar un certificado</a>
                </div>
            </div>
        </section>

        @if ($curso->testimoniosLista() !== [])
            <section class="ficha-bloque bg-white" aria-label="Testimonios">
                <div class="mx-auto max-w-6xl px-4">
                    <h2 class="text-3xl font-extrabold text-marca">Testimonios</h2>
                    <p class="mt-2 text-sm">POR CONFIRMAR mientras la empresa no entregue citas autorizadas.</p>
                    <div class="mt-6 flex snap-x snap-mandatory gap-4 overflow-x-auto pb-2">
                        @foreach ($curso->testimoniosLista() as $testimonio)
                            <article class="tarjeta-suave w-[85%] shrink-0 snap-start p-5 sm:w-[calc(50%-0.5rem)]">
                                <p class="text-xs font-semibold uppercase tracking-wide text-acento">POR CONFIRMAR</p>
                                <p class="mt-3">{{ $testimonio['texto'] }}</p>
                                <p class="mt-4 font-semibold text-marca">{{ $testimonio['nombre'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($curso->preguntasLista() !== [])
            <section class="ficha-bloque fondo-suave">
                <div class="mx-auto max-w-3xl px-4">
                    <h2 class="text-3xl font-extrabold text-marca">Preguntas frecuentes</h2>
                    <div class="mt-4 space-y-3">
                        @foreach ($curso->preguntasLista() as $pregunta)
                            <details class="tarjeta-suave p-4">
                                <summary class="cursor-pointer font-semibold text-marca">{{ $pregunta['pregunta'] }}</summary>
                                <p class="mt-3 text-sm leading-6">{{ $pregunta['respuesta'] }}</p>
                            </details>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        @if ($relacionados->isNotEmpty())
            <section class="ficha-bloque bg-white">
                <div class="mx-auto max-w-6xl px-4">
                    <h2 class="text-3xl font-extrabold text-marca">Cursos relacionados</h2>
                    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($relacionados as $relacionado)
                            @include('partials.curso-card', ['curso' => $relacionado])
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
    </article>

    <div class="barra-inscripcion border-t border-fondo/10 bg-white px-4 py-3 shadow">
        <div class="flex items-center justify-between gap-3">
            <p class="font-extrabold text-marca">{{ $curso->sePuedeComprar() ? $curso->precioFormateado() : 'Consultar' }}</p>
            @if ($curso->sePuedeComprar())
                <a class="fuente-ui rounded-full bg-marca-2 px-5 py-3 text-sm font-semibold text-white" href="{{ route('compra.crear', $curso) }}">Inscribirme</a>
            @else
                <a class="fuente-ui rounded-full border border-marca px-5 py-3 text-sm font-semibold text-marca" href="{{ $marca->enlaceWhatsapp('Hola, quiero consultar el curso '.$curso->titulo) }}">Consultar</a>
            @endif
        </div>
    </div>
@endsection
