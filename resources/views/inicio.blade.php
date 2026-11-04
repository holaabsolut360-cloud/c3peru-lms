@extends('layouts.publico')
@section('titulo', $marca->nombre_comercial.' · Capacitación y certificación')
@section('descripcion', $marca->meta_descripcion)

@section('contenido')
    <section id="empresa" class="relative overflow-hidden bg-fondo text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-fondo via-fondo-alt to-marca"></div>
        <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 32px 32px;"></div>

        <div class="relative mx-auto max-w-6xl px-4 py-16 sm:px-6 lg:px-8 lg:py-24">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                <div class="max-w-2xl">
                    <div class="fuente-ui mb-7 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm font-medium text-white/90">
                        <span class="h-2 w-2 rounded-full bg-acento"></span>
                        Capacitación técnica para la industria
                    </div>

                    <h1 class="max-w-2xl text-4xl font-extrabold leading-tight text-white sm:text-5xl lg:text-6xl">
                        Aprende haciendo, <span class="text-acento">aprende con expertos.</span>
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-relaxed text-white/80 sm:text-lg">
                        Capacitación y certificación de personas en Izaje y Manejo de Cargas, Ensayos No Destructivos, Oil &amp; Gas y Técnicas de Mantenimiento.
                    </p>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a class="fuente-ui inline-flex items-center justify-center gap-2 rounded-lg bg-acento px-6 py-3.5 font-semibold text-fondo transition-colors hover:bg-white" href="{{ route('cursos.index') }}">
                            Explorar cursos <i class="ti ti-arrow-right" aria-hidden="true"></i>
                        </a>
                        <a class="fuente-ui inline-flex items-center justify-center gap-2 rounded-lg border border-white/30 px-6 py-3.5 font-semibold text-white transition-colors hover:bg-white/10" href="{{ route('certificados.formulario') }}">
                            <i class="ti ti-shield-check" aria-hidden="true"></i> Verificar certificado
                        </a>
                    </div>

                    <div class="mt-9 flex flex-wrap gap-x-6 gap-y-3 border-t border-white/15 pt-6 text-xs font-semibold uppercase tracking-wider text-white/65">
                        <span class="flex items-center gap-2"><i class="ti ti-certificate text-acento" aria-hidden="true"></i> SNT-TC-1A</span>
                        <span class="flex items-center gap-2"><i class="ti ti-certificate text-acento" aria-hidden="true"></i> ISO 9712</span>
                        <span class="flex items-center gap-2"><i class="ti ti-certificate text-acento" aria-hidden="true"></i> ISO 18436</span>
                    </div>
                </div>

                <div class="relative hidden lg:block">
                    <div class="aspect-video overflow-hidden rounded-xl border border-white/15 bg-fondo shadow-2xl">
                        @if ($marca->videoIncrustado())
                            <iframe class="h-full w-full" src="{{ $marca->videoIncrustado() }}" title="Video institucional de {{ $marca->nombre_comercial }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        @elseif ($marca->videoArchivoPublico())
                            <video class="h-full w-full object-cover" controls preload="metadata" poster="/marca/hero.jpg">
                                <source src="{{ $marca->videoArchivoPublico() }}">
                            </video>
                        @else
                            <img class="h-full w-full object-cover" src="/marca/hero.jpg" alt="Capacitación técnica de C3 Perú">
                        @endif
                    </div>
                    <div class="absolute -bottom-6 -left-5 max-w-xs rounded-lg border border-slate-100 bg-white p-4 text-fondo shadow-xl">
                        <div class="flex items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-acento/20 text-xl text-marca"><i class="ti ti-certificate" aria-hidden="true"></i></span>
                            <div>
                                <p class="fuente-ui text-[11px] font-semibold uppercase tracking-wide text-fondo/60">Certificados</p>
                                <p class="text-sm font-bold">Verificables por DNI, CE o código</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-12 text-sm text-white/75">
                ¿Necesitas asesoría? <a class="font-semibold text-white underline underline-offset-4" href="{{ $marca->enlaceWhatsapp('Hola, quiero información sobre los cursos de C3 Perú.') }}" target="_blank" rel="noopener">Escríbenos por WhatsApp: {{ $marca->telefono }}</a>
            </p>
        </div>
    </section>

    <section id="especialidades" class="bg-fondo py-14 text-white">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white sm:text-4xl">4 especialidades para la industria</h2>
            <p class="mt-3 max-w-3xl leading-relaxed text-white/75">Programas de capacitación y certificación liderados por profesionales de reconocida trayectoria. La acreditación formal bajo NTP ISO/IEC 17024 está pendiente de confirmación documental.</p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($especialidades as $indice => $especialidad)
                    <article class="flex flex-col rounded-lg border border-white/10 border-t-4 border-t-acento bg-fondo-alt p-5">
                        <p class="fuente-ui text-xs font-semibold uppercase tracking-wide text-acento">Especialidad {{ $indice + 1 }}</p>
                        <h3 class="mt-2 text-xl font-bold text-white">{{ $especialidad->nombre === 'Izaje' ? 'Izaje y Manejo de Cargas' : ($especialidad->nombre === 'Mantenimiento' ? 'Confiabilidad y Técnicas de Mantenimiento' : $especialidad->nombre) }}</h3>
                        <p class="mt-2 text-sm text-white/70">{{ $especialidad->descripcion }}</p>
                        <ul class="mt-4 space-y-2 text-sm text-white/85">
                            @foreach ($especialidad->cursos->take(4) as $curso)
                                <li><a class="text-white hover:text-acento" href="{{ route('cursos.ver', $curso) }}">{{ $curso->titulo }}</a></li>
                            @endforeach
                        </ul>
                        <a class="fuente-ui mt-auto pt-5 text-sm font-semibold text-acento" href="{{ route('cursos.index', ['categoria' => $especialidad->slug]) }}">Ver cursos <i class="ti ti-arrow-right" aria-hidden="true"></i></a>
                    </article>
                @endforeach
            </div>
            <div class="mt-6 flex flex-wrap gap-2 text-sm">
                @foreach (['SNT-TC-1A', 'ISO 9712', 'ISO 18436', 'NTP ISO/IEC 17024'] as $esquema)
                    <span class="rounded-full border border-acento/60 px-3 py-1 text-acento">{{ $esquema }}</span>
                @endforeach
            </div>
        </div>
    </section>

    <section id="cursos" class="bg-white py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <div class="mb-10 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                <div class="max-w-2xl">
                    <p class="fuente-ui text-sm font-semibold uppercase tracking-wider text-marca">Oferta académica</p>
                    <h2 class="mt-2 text-3xl font-bold text-fondo sm:text-4xl">Cursos destacados 2026</h2>
                    <p class="mt-3 leading-relaxed text-fondo/70">Programas técnicos diseñados para fortalecer las competencias de tu equipo.</p>
                </div>
                <a class="fuente-ui inline-flex items-center gap-2 font-semibold text-marca hover:text-marca-2" href="{{ route('cursos.index') }}">
                    Ver catálogo completo <i class="ti ti-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($destacados as $curso)
                    <article class="group flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white transition-shadow hover:shadow-lg">
                        <a class="relative block aspect-[16/10] overflow-hidden bg-fondo" href="{{ route('cursos.ver', $curso) }}">
                            <img src="{{ $curso->imagenPortada() }}" alt="{{ $curso->titulo }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            <span class="fuente-ui absolute left-4 top-4 rounded-md bg-white/95 px-3 py-1.5 text-xs font-semibold uppercase text-marca">{{ $curso->categoria?->nombre ?? 'General' }}</span>
                        </a>

                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="text-xl font-bold leading-snug text-fondo group-hover:text-marca">
                                <a class="text-inherit" href="{{ route('cursos.ver', $curso) }}">{{ $curso->titulo }}</a>
                            </h3>
                            <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-fondo/70">{{ $curso->descripcion ?: 'Desarrolla habilidades técnicas con el acompañamiento de especialistas.' }}</p>

                            <div class="mt-5 flex flex-wrap gap-x-4 gap-y-2 border-b border-slate-100 pb-5 text-sm text-fondo/70">
                                @if ($curso->duracion)
                                    <span class="flex items-center gap-1.5"><i class="ti ti-clock text-marca" aria-hidden="true"></i>{{ $curso->duracion }}</span>
                                @endif
                                @if ($curso->modalidad)
                                    <span class="flex items-center gap-1.5"><i class="ti ti-school text-marca" aria-hidden="true"></i>{{ $curso->modalidad }}</span>
                                @endif
                            </div>

                            <div class="mt-auto flex items-end justify-between gap-3 pt-5">
                                <div>
                                    <span class="block text-xs text-fondo/60">{{ $curso->incluye_igv ? 'Precio, incluye IGV' : 'Precio' }}</span>
                                    <span class="mt-1 block text-xl font-bold text-fondo">{{ $curso->sePuedeComprar() ? $curso->precioFormateado() : 'Consultar' }}</span>
                                </div>
                                <a class="fuente-ui inline-flex shrink-0 items-center gap-2 rounded-lg bg-marca px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-marca-2" href="{{ route('cursos.ver', $curso) }}">
                                    Ver curso <i class="ti ti-arrow-right" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="col-span-full rounded-lg border border-dashed border-slate-300 px-6 py-12 text-center text-fondo/70">Próximamente publicaremos nuevos cursos destacados.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section id="modalidades" class="bg-slate-50 py-14">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-marca sm:text-4xl">Modalidades</h2>
            <p class="mt-2 max-w-2xl text-fondo/70">Virtual, semipresencial o presencial. Elige cómo entrenar a tu equipo.</p>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <article class="rounded-lg border border-slate-200 bg-white p-5">
                    <h3 class="text-xl font-bold text-marca">Virtual</h3>
                    <p class="mt-2 text-sm leading-relaxed text-fondo/75">E-learning a tu ritmo y sesiones en línea, con material y soporte en el aula.</p>
                </article>
                <article class="rounded-lg border border-acento bg-fondo p-5 text-white">
                    <h3 class="text-xl font-bold">Semipresencial</h3>
                    <p class="mt-2 text-sm leading-relaxed text-white/80">Teoría en la plataforma y práctica presencial en Lima, con equipos e insumos.</p>
                </article>
                <article class="rounded-lg border border-slate-200 bg-white p-5">
                    <h3 class="text-xl font-bold text-marca">Presencial</h3>
                    <p class="mt-2 text-sm leading-relaxed text-fondo/75">Entrenamiento en aula o in company, coordinado según las necesidades de tu operación.</p>
                </article>
            </div>
            <a class="fuente-ui mt-5 inline-flex items-center gap-2 font-semibold text-marca" href="{{ route('paginas.ver', 'modalidades-de-entrenamiento') }}">Ver modalidades <i class="ti ti-arrow-right" aria-hidden="true"></i></a>
        </div>
    </section>

    @include('partials.comunidad', ['conCierre' => false])

    <section id="preguntas" class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-marca">Preguntas frecuentes</h2>
        <div class="mt-5 space-y-3">
            <details class="rounded-lg border border-slate-200 p-4" open>
                <summary class="fuente-ui cursor-pointer font-semibold">¿Pueden dictar el curso en nuestra empresa?</summary>
                <p class="mt-2 text-sm leading-relaxed text-fondo/75">Sí. Coordinamos entrenamientos presenciales in company de acuerdo con los requerimientos de tu operación.</p>
            </details>
            <details class="rounded-lg border border-slate-200 p-4">
                <summary class="fuente-ui cursor-pointer font-semibold">¿Entregan certificados?</summary>
                <p class="mt-2 text-sm leading-relaxed text-fondo/75">Sí. La certificación depende del esquema correspondiente a cada curso. Los certificados emitidos se pueden verificar por DNI, CE o código.</p>
            </details>
            <details class="rounded-lg border border-slate-200 p-4">
                <summary class="fuente-ui cursor-pointer font-semibold">¿Los precios incluyen IGV?</summary>
                <p class="mt-2 text-sm leading-relaxed text-fondo/75">La ficha de cada curso indica si el precio publicado incluye IGV. Las inscripciones quedan sujetas a la aprobación del comprobante de pago.</p>
            </details>
        </div>
    </section>

    <section id="contacto" class="bg-slate-50 py-14">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 sm:px-6 md:grid-cols-2 lg:px-8">
            <div>
                <h2 class="text-3xl font-bold text-marca">Contáctanos</h2>
                <p class="mt-4 text-fondo/80">{{ $marca->correo }}</p>
                <p class="text-fondo/80">{{ $marca->telefono }}</p>
                <p class="mt-1 text-fondo/80">{{ $marca->direccion }}</p>
            </div>
            <form method="post" action="{{ route('contacto.guardar') }}" class="space-y-3">
                @csrf
                <label class="sr-only" for="contacto-nombre">Nombre</label>
                <input id="contacto-nombre" name="nombre" required placeholder="Nombre" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3">
                <label class="sr-only" for="contacto-correo">Correo</label>
                <input id="contacto-correo" name="correo" type="email" required placeholder="Correo" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3">
                <label class="sr-only" for="contacto-mensaje">Mensaje</label>
                <textarea id="contacto-mensaje" name="mensaje" required rows="4" placeholder="Cuéntanos qué necesita tu operación" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3"></textarea>
                <button class="accion-principal fuente-ui rounded-lg bg-marca px-5 py-3 font-semibold text-white" type="submit">Enviar consulta</button>
            </form>
        </div>
    </section>
@endsection
