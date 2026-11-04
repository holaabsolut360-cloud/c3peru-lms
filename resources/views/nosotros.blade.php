@extends('layouts.publico')
@section('titulo', 'Nosotros · '.$marca->nombre_comercial)

@section('contenido')
    <article class="text-left">
        <header class="py-10 md:py-14">
            <div class="relative mx-auto max-w-6xl overflow-hidden px-4 py-6">
                <div class="pointer-events-none absolute left-0 top-0 h-44 w-44 rounded-full bg-acento/70 blur-3xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute bottom-0 right-0 h-56 w-56 rounded-full bg-acento/60 blur-3xl" aria-hidden="true"></div>
                <div class="pointer-events-none absolute left-1/3 top-8 h-24 w-24 rounded-full bg-acento/40 blur-2xl" aria-hidden="true"></div>
                <div class="relative m-3 min-h-[26rem] overflow-hidden rounded-3xl bg-fondo md:m-5 md:min-h-[32rem]">
                    <img src="/marca/hero.webp" width="1400" height="1867" alt="Operación industrial en un tajo abierto, escenario de la capacitación de C3 Perú" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
                    <div class="velo-45 absolute inset-0"></div>
                    <div class="relative flex min-h-[26rem] max-w-3xl flex-col justify-end p-6 text-white md:min-h-[32rem] md:p-12">
                        <p class="text-sm font-semibold uppercase tracking-[0.14em] text-acento">{{ $marca->nombre_comercial }}</p>
                        <h1 class="mt-3 text-4xl font-extrabold leading-tight text-white md:text-6xl">Sobre {{ $marca->nombre_comercial }}</h1>
                        <p class="mt-4 max-w-xl text-lg font-semibold text-acento">¡Aprende haciendo, aprende con expertos!</p>
                        <p class="mt-3 max-w-xl text-white/90">Capacitación, certificación y calidad para Izaje, Ensayos No Destructivos, Oil &amp; Gas y Mantenimiento.</p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a class="fuente-ui rounded-full bg-acento px-7 py-3 font-semibold text-fondo" href="{{ route('cursos.index') }}">Ver cursos</a>
                            <a class="fuente-ui rounded-full border border-white px-7 py-3 font-semibold text-white" href="{{ route('contacto') }}">Contáctanos</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section id="quienes-somos" class="ficha-bloque bg-white">
            <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 lg:grid-cols-2">
                <div>
                    <h2 class="text-3xl font-extrabold text-marca md:text-4xl">Quiénes somos</h2>
                    <p class="mt-4 text-lg font-semibold text-marca">Creamos experiencias únicas.</p>
                    <p class="mt-3 leading-7">C3 Peru SAC es tu aliado estratégico en la formación y certificación de personal, impulsando el crecimiento y la excelencia profesional. Con un enfoque serio, innovador y personalizado, transformamos el potencial de tu equipo en resultados tangibles.</p>
                </div>
                <div class="relative grid min-w-0 grid-cols-2 gap-3">
                    <img src="/marca/izajes.webp" width="1400" height="933" alt="Maniobra de izaje en una operación industrial" class="col-span-2 h-52 w-full rounded-3xl object-cover md:h-64" loading="lazy">
                    <img src="/marca/end.webp" width="1400" height="1750" alt="Inspección de ensayos no destructivos" class="h-36 w-full rounded-3xl object-cover md:h-44" loading="lazy">
                    <img src="/marca/oilgas.webp" width="1400" height="1867" alt="Instalación del sector oil y gas" class="h-36 w-full rounded-3xl object-cover md:h-44" loading="lazy">
                    <div class="tarjeta-suave absolute bottom-6 left-6 max-w-[14rem] p-4">
                        <p class="text-xs font-semibold uppercase tracking-wide text-acento">Certificación</p>
                        <p class="mt-1 font-extrabold text-marca">ISO/IEC 17024</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="vision" class="ficha-bloque fondo-suave">
            <div class="mx-auto grid max-w-6xl gap-4 px-4 md:grid-cols-2">
                <article id="mision" class="relative min-h-80 overflow-hidden rounded-2xl p-8 text-white" style="background: linear-gradient(160deg, rgb(36 51 107 / 0.94), rgb(36 51 107 / 0.78)), url('/marca/izajes.webp') center/cover">
                    <i class="ti ti-target text-5xl text-acento" aria-hidden="true"></i>
                    <h2 class="mt-4 text-3xl font-extrabold text-white">Misión</h2>
                    <p class="mt-3 text-lg font-semibold">Liderando la Excelencia Industrial en Latinoamérica.</p>
                    <p class="mt-3 leading-7 text-white/90">Tu mejor aliado en entrenamiento, capacitación y certificación especializada en Izaje de Cargas, Ensayos No Destructivos y sector Oil &amp; Gas. Elevamos los estándares de seguridad y calidad.</p>
                </article>
                <article class="relative min-h-80 overflow-hidden rounded-2xl p-8 text-white" style="background: linear-gradient(160deg, rgb(24 70 145 / 0.94), rgb(24 70 145 / 0.78)), url('/marca/end.webp') center/cover">
                    <i class="ti ti-telescope text-5xl text-acento" aria-hidden="true"></i>
                    <h2 class="mt-4 text-3xl font-extrabold text-white">Visión</h2>
                    <p class="mt-3 leading-7 text-white/90">Ser el referente latinoamericano en la gestión del conocimiento técnico, liderando la capacitación especializada y la Certificación de Personas bajo la norma NTP ISO/IEC 17024, elevando los estándares de competencia en la industria.</p>
                </article>
            </div>
        </section>

        <section id="compromiso" class="ficha-bloque bg-white">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-3xl font-extrabold text-marca">Nuestro compromiso</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    @forelse ($compromisos as $compromiso)
                        <article class="tarjeta-suave p-6">
                            @if ($compromiso->propuesto)
                                <p class="text-xs font-semibold uppercase tracking-wide text-acento">Texto propuesto, por confirmar con el cliente</p>
                            @endif
                            <h3 class="mt-2 text-xl font-extrabold text-marca">{{ $compromiso->titulo }}</h3>
                            <p class="mt-3 leading-7">{{ $compromiso->texto }}</p>
                        </article>
                    @empty
                        <p>POR CONFIRMAR. El texto de compromiso todavía no fue aprobado.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="ficha-bloque fondo-suave" aria-label="Especialidades">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-3xl font-extrabold text-marca">Nuestras 4 especialidades</h2>
                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    @foreach ([
                        ['izaje', 'Izajes y Manejo de Cargas', '/marca/izajes.webp', 1400, 933, 'Maniobra de izaje'],
                        ['end-ndt', 'Ensayos No Destructivos', '/marca/end.webp', 1400, 1750, 'Inspección por ensayos no destructivos'],
                        ['mantenimiento', 'Técnicas de Mantenimiento', '/marca/mantenimiento.webp', 1400, 934, 'Técnico de mantenimiento industrial'],
                        ['oil-gas', 'Oil & Gas', '/marca/oilgas.webp', 1400, 1867, 'Instalación de oil y gas'],
                    ] as [$slug, $titulo, $foto, $ancho, $alto, $alt])
                        <a href="{{ route('cursos.index', ['categoria' => $slug]) }}" class="group relative block h-64 overflow-hidden rounded-2xl">
                            <img src="{{ $foto }}" width="{{ $ancho }}" height="{{ $alto }}" alt="{{ $alt }}" class="zoom-foto h-full w-full object-cover" loading="lazy">
                            <div class="absolute inset-0 bg-fondo/55"></div>
                            <div class="absolute inset-x-0 bottom-0 flex items-end justify-between p-5 text-white">
                                <h3 class="text-2xl font-extrabold text-white">{{ $titulo }}</h3>
                                <i class="ti ti-arrow-right text-2xl text-acento" aria-hidden="true"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="modalidades" class="ficha-bloque bg-white">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-3xl font-extrabold text-marca">Modalidades</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <article class="tarjeta-suave p-6">
                        <i class="ti ti-device-laptop text-5xl text-acento" aria-hidden="true"></i>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-acento">A tu ritmo</p>
                        <h3 class="mt-1 text-xl font-extrabold text-marca">E-learning</h3>
                        <p class="mt-3 text-sm leading-6">Aprende a tu ritmo con clases teóricas y demostraciones en video y material interactivo.</p>
                    </article>
                    <article class="tarjeta-suave p-6">
                        <i class="ti ti-building-factory text-5xl text-acento" aria-hidden="true"></i>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-acento">En Lima o in company</p>
                        <h3 class="mt-1 text-xl font-extrabold text-marca">Presencial</h3>
                        <p class="mt-3 text-sm leading-6">Prácticas presenciales en Lima con equipos e insumos, o entrenamiento 100 % presencial si tu empresa lo requiere.</p>
                    </article>
                    <article class="tarjeta-suave p-6">
                        <i class="ti ti-video text-5xl text-acento" aria-hidden="true"></i>
                        <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-acento">En vivo</p>
                        <h3 class="mt-1 text-xl font-extrabold text-marca">Digital</h3>
                        <p class="mt-3 text-sm leading-6">Virtual sincrónico con paquetes adaptados. También combinamos E-learning + presencial + online.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="equipo" class="ficha-bloque fondo-suave">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-3xl font-extrabold text-marca">Equipo</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($instructores as $persona)
                        <article class="tarjeta-suave overflow-hidden">
                            @if ($persona->foto)
                                <img src="{{ $persona->foto }}" width="640" height="800" alt="{{ $persona->nombre }}, {{ $persona->cargo }}" class="h-72 w-full object-cover" loading="lazy">
                            @else
                                <div class="flex h-72 flex-col items-center justify-center bg-gradient-to-br from-marca to-marca-2 text-white">
                                    <span class="text-6xl font-extrabold" aria-hidden="true">{{ $persona->iniciales }}</span>
                                    <p class="mt-3 text-xs font-semibold uppercase tracking-wide">Foto POR CONFIRMAR</p>
                                </div>
                            @endif
                            <div class="p-5">
                                <h3 class="text-xl font-extrabold text-marca">{{ $persona->nombre }}</h3>
                                <p class="text-sm font-semibold text-marca-2">{{ $persona->cargo }}</p>
                                @if ($persona->credenciales)
                                    <ul class="mt-3 flex flex-wrap gap-2">
                                        @foreach ($persona->credenciales as $credencial)
                                            <li class="rounded-full border border-acento px-3 py-1 text-xs font-semibold text-marca">{{ $credencial }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                <p class="mt-3 text-sm leading-6">{{ $persona->bio }}</p>
                                @if ($persona->linkedin)
                                    <a class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-marca-2" href="{{ $persona->linkedin }}">
                                        <i class="ti ti-brand-linkedin" aria-hidden="true"></i> LinkedIn
                                    </a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        @include('partials.comunidad', ['conCierre' => true])
    </article>
@endsection
