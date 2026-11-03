@extends('layouts.publico')
@section('titulo', 'Nosotros · '.$marca->nombre_comercial)

@section('contenido')
    <article class="text-left">
        <header class="ficha-bloque bg-fondo text-white">
            <div class="mx-auto max-w-6xl px-4">
                <p class="text-sm font-semibold uppercase tracking-[0.14em] text-acento">{{ $marca->nombre_comercial }}</p>
                <h1 class="mt-3 max-w-3xl text-4xl font-extrabold text-white md:text-5xl">{{ $marca->eslogan }}</h1>
                <p class="mt-4 max-w-2xl text-white/85">Formación y certificación de personal en izaje, ensayos no destructivos, oil &amp; gas y mantenimiento.</p>
            </div>
        </header>

        <section id="quienes-somos" class="ficha-bloque bg-white">
            <div class="mx-auto max-w-3xl px-4">
                <h2 class="text-3xl font-extrabold text-marca">Quiénes somos</h2>
                <p class="mt-4 text-lg font-semibold text-marca">Creamos experiencias únicas.</p>
                <p class="mt-3 leading-7">C3 Peru SAC es tu aliado estratégico en la formación y certificación de personal, impulsando el crecimiento y la excelencia profesional. Con un enfoque serio, innovador y personalizado, transformamos el potencial de tu equipo en resultados tangibles.</p>
            </div>
        </section>

        <section id="vision" class="ficha-bloque fondo-suave">
            <div class="mx-auto grid max-w-6xl gap-4 px-4 md:grid-cols-2">
                <article id="mision" class="tarjeta-suave p-6">
                    <h2 class="text-2xl font-extrabold text-marca">Misión</h2>
                    <p class="mt-3 font-semibold">Liderando la Excelencia Industrial en Latinoamérica.</p>
                    <p class="mt-3 leading-7">Tu mejor aliado en entrenamiento, capacitación y certificación especializada en Izaje de Cargas, Ensayos No Destructivos y sector Oil &amp; Gas. Elevamos los estándares de seguridad y calidad.</p>
                </article>
                <article class="tarjeta-suave p-6">
                    <h2 class="text-2xl font-extrabold text-marca">Visión</h2>
                    <p class="mt-3 leading-7">Ser el referente latinoamericano en la gestión del conocimiento técnico, liderando la capacitación especializada y la Certificación de Personas bajo la norma NTP ISO/IEC 17024, elevando los estándares de competencia en la industria.</p>
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

        <section id="modalidades" class="ficha-bloque fondo-suave">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-3xl font-extrabold text-marca">Modalidades</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <article class="tarjeta-suave p-5">
                        <h3 class="text-xl font-extrabold text-marca">E-learning</h3>
                        <p class="mt-3 text-sm leading-6">Aprende a tu ritmo con clases teóricas y demostraciones en video y material interactivo.</p>
                    </article>
                    <article class="tarjeta-suave p-5">
                        <h3 class="text-xl font-extrabold text-marca">Presencial</h3>
                        <p class="mt-3 text-sm leading-6">Prácticas presenciales en Lima con equipos e insumos, o entrenamiento 100 % presencial si tu empresa lo requiere.</p>
                    </article>
                    <article class="tarjeta-suave p-5">
                        <h3 class="text-xl font-extrabold text-marca">Digital</h3>
                        <p class="mt-3 text-sm leading-6">Virtual sincrónico con paquetes adaptados. También combinamos E-learning + presencial + online.</p>
                    </article>
                </div>
            </div>
        </section>

        <section id="equipo" class="ficha-bloque bg-white">
            <div class="mx-auto max-w-6xl px-4">
                <h2 class="text-3xl font-extrabold text-marca">Equipo</h2>
                <p class="mt-2 text-sm">Las fotos de los instructores están POR CONFIRMAR. Mientras tanto se muestran las iniciales.</p>
                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    @foreach ($instructores as $persona)
                        <article class="tarjeta-suave p-5">
                            @if ($persona->foto)
                                <img src="{{ $persona->foto }}" alt="{{ $persona->nombre }}" class="h-20 w-20 rounded-full object-cover">
                            @else
                                <span class="flex h-20 w-20 items-center justify-center rounded-full bg-marca text-xl font-extrabold text-white" aria-hidden="true">{{ $persona->iniciales }}</span>
                                <p class="mt-2 text-xs font-semibold uppercase tracking-wide text-acento">Foto POR CONFIRMAR</p>
                            @endif
                            <h3 class="mt-3 text-xl font-extrabold text-marca">{{ $persona->nombre }}</h3>
                            <p class="text-sm font-semibold text-marca-2">{{ $persona->cargo }}</p>
                            <p class="mt-3 text-sm leading-6">{{ $persona->bio }}</p>
                            @if ($persona->linkedin)
                                <a class="mt-3 inline-block text-sm font-semibold text-marca-2" href="{{ $persona->linkedin }}">LinkedIn</a>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        @include('partials.comunidad', ['conCierre' => true])
    </article>
@endsection
