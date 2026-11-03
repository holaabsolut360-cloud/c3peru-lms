@extends('layouts.publico')
@section('titulo', $marca->nombre_comercial.' · Capacitación y certificación')

@section('contenido')
    <section id="empresa" class="relative overflow-hidden bg-fondo text-white">
        <img src="/marca/hero.jpg" alt="" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-fondo via-fondo/85 to-fondo/25"></div>
        <div class="relative mx-auto max-w-6xl px-4 py-16 md:py-24 lg:py-28">
            <p class="fuente-ui text-sm font-semibold uppercase tracking-[0.16em] text-acento">{{ $marca->lema }}</p>
            <h1 class="mt-4 max-w-3xl text-4xl leading-tight md:text-6xl">Aprende haciendo, <span class="text-acento">aprende con expertos</span></h1>
            <p class="mt-5 max-w-2xl text-base text-white/90 md:text-lg">Capacitación y certificación de personas en Izaje y Manejo de Cargas, Ensayos No Destructivos, Oil &amp; Gas y Técnicas de Mantenimiento. Esquemas SNT-TC-1A, ISO 9712 e ISO 18436, con capacitadores ASNT NDT Level III.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a class="fuente-ui rounded-lg bg-acento px-5 py-3 font-semibold text-fondo" href="{{ route('cursos.index') }}">Explorar cursos</a>
                <a class="fuente-ui rounded-lg border border-white px-5 py-3 font-semibold text-white" href="{{ route('certificados.formulario') }}">Verificar certificado</a>
            </div>
            <p class="mt-6 text-sm text-white/90">
                <a class="text-white underline-offset-2 hover:underline" href="{{ $marca->enlaceWhatsapp('Hola, quiero información sobre los cursos de C3 Perú.') }}">Escríbenos por WhatsApp: {{ $marca->telefono }}</a>
            </p>
            @if ($marca->videoIncrustado())
                <div class="mt-8 aspect-video max-w-3xl overflow-hidden rounded-2xl bg-black shadow-xl">
                    <iframe class="h-full w-full" src="{{ $marca->videoIncrustado() }}" title="Video institucional de {{ $marca->nombre_comercial }}" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            @elseif ($marca->videoArchivoPublico())
                <video class="mt-8 max-w-3xl w-full rounded-2xl bg-black" controls preload="metadata" poster="/marca/hero.jpg">
                    <source src="{{ $marca->videoArchivoPublico() }}">
                </video>
            @endif
        </div>
    </section>

    <section id="especialidades" class="bg-fondo py-14 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-3xl md:text-4xl">4 especialidades para la industria</h2>
            <p class="mt-3 max-w-3xl text-white/80">Somos un organismo de capacitación y certificación de personas. Los programas están liderados por profesionales de reconocida trayectoria. La acreditación formal bajo NTP ISO/IEC 17024 está pendiente de confirmación documental.</p>
            <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach ($especialidades as $indice => $especialidad)
                    <article class="flex flex-col rounded-2xl border border-white/10 border-t-4 border-t-acento bg-fondo-alt p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-acento">Especialidad {{ $indice + 1 }}</p>
                        <h3 class="mt-2 text-2xl">{{ $especialidad->nombre === 'Izaje' ? 'Izaje y Manejo de Cargas' : ($especialidad->nombre === 'Mantenimiento' ? 'Confiabilidad y Técnicas de Mantenimiento' : $especialidad->nombre) }}</h3>
                        <p class="mt-1 text-sm text-acento">{{ $especialidad->descripcion }}</p>
                        <ul class="mt-4 space-y-1 text-sm text-white/85">
                            @foreach ($especialidad->cursos->take(8) as $curso)
                                <li><a class="text-white" href="{{ route('cursos.ver', $curso) }}">{{ $curso->titulo }}@if($curso->nivel) — {{ $curso->nivel }}@endif</a></li>
                            @endforeach
                        </ul>
                        <a class="mt-auto pt-4 text-sm font-semibold text-acento" href="{{ route('cursos.index', ['categoria' => $especialidad->slug]) }}">Ver especialidad →</a>
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

    <section id="cursos" class="bg-fondo-alt py-14 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <p class="fuente-ui text-sm font-semibold uppercase tracking-wide text-acento">Catálogo de cursos 2026</p>
            <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl">Explora nuestros cursos</h2>
                    <p class="mt-2 max-w-2xl text-white/80">Cursos destacados del programa semipresencial NDT 2026. Precios en soles, incluyen IGV.</p>
                </div>
            </div>
            <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                @forelse ($destacados as $curso)
                    @include('partials.curso-card', ['curso' => $curso])
                @empty
                    <p>El catálogo se publica al cargar la base de cursos.</p>
                @endforelse
            </div>
            <div class="mt-8 text-center">
                <a class="fuente-ui inline-block rounded-lg bg-acento px-6 py-3 font-semibold text-fondo" href="{{ route('cursos.index') }}">Explorar más cursos</a>
            </div>
        </div>
    </section>

    <section id="modalidades" class="bg-white py-14">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-3xl text-marca">Modalidades</h2>
            <p class="mt-2 max-w-2xl">Virtual, semipresencial o presencial. Tú eliges cómo entrenar a tu equipo.</p>
            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <article class="rounded-2xl border border-marca/10 p-5">
                    <h3 class="text-xl text-marca">Virtual</h3>
                    <p class="mt-2 text-sm">E-learning a tu ritmo y sesiones en línea sincrónicas, con material y soporte en el aula.</p>
                </article>
                <article class="rounded-2xl border border-acento bg-fondo p-5 text-white">
                    <h3 class="text-xl">Semipresencial</h3>
                    <p class="mt-2 text-sm text-white/80">Teoría en la plataforma y práctica presencial en Lima, con equipos e insumos.</p>
                </article>
                <article class="rounded-2xl border border-marca/10 p-5">
                    <h3 class="text-xl text-marca">Presencial</h3>
                    <p class="mt-2 text-sm">Entrenamiento en aula o inhouse, en tu unidad, si la operación lo requiere.</p>
                </article>
            </div>
            <a class="fuente-ui mt-4 inline-block font-semibold" href="{{ route('paginas.ver', 'modalidades-de-entrenamiento') }}">Ver modalidades</a>
        </div>
    </section>

    <section id="clientes" class="bg-fondo py-14 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-3xl">Nuestros clientes, aliados y alianzas</h2>
            <p class="mt-2 max-w-3xl text-white/75">Los nombres, logos y citas siguientes son ejemplos. Cada tarjeta marcada POR CONFIRMAR espera el texto y la autorización de la empresa.</p>
            @foreach (['testimonio' => 'Testimoniales', 'aliado' => 'Aliados', 'alianza' => 'Alianzas'] as $tipo => $titulo)
                <h3 class="mt-8 text-xl text-acento">{{ $titulo }}</h3>
                <div class="mt-4 grid gap-4 md:grid-cols-2">
                    @forelse (($referencias[$tipo] ?? collect()) as $referencia)
                        <article class="rounded-2xl border border-white/10 bg-fondo-alt p-5">
                            @if ($referencia->por_confirmar)
                                <p class="text-xs font-semibold uppercase tracking-wide text-acento">POR CONFIRMAR</p>
                            @endif
                            <h4 class="mt-1 text-lg">{{ $referencia->nombre }}</h4>
                            @if ($referencia->detalle)<p class="text-sm text-white/70">{{ $referencia->detalle }}</p>@endif
                            @if ($referencia->texto)<p class="mt-3 text-sm text-white/85">{{ $referencia->texto }}</p>@endif
                        </article>
                    @empty
                        <p class="text-sm text-white/70">POR CONFIRMAR. Aún no hay registros de este grupo.</p>
                    @endforelse
                </div>
            @endforeach
        </div>
    </section>

    <section id="preguntas" class="mx-auto max-w-6xl px-4 py-14">
        <h2 class="text-3xl text-marca">Preguntas frecuentes</h2>
        <div class="mt-4 space-y-4">
            <details class="rounded-xl border p-4" open>
                <summary class="fuente-ui cursor-pointer font-semibold">¿Pueden dictar el curso en nuestra empresa?</summary>
                <p class="mt-2">Sí. Con cursos inhouse el entrenamiento es 100 % presencial, y con cursos adhoc se adapta a tus requerimientos.</p>
            </details>
            <details class="rounded-xl border p-4">
                <summary class="fuente-ui cursor-pointer font-semibold">¿Entregan certificados?</summary>
                <p class="mt-2">Sí. Certificamos de acuerdo con SNT-TC-1A, ISO 9712 o ISO 18436 según el curso, y el certificado se puede verificar por DNI, CE o código.</p>
            </details>
            <details class="rounded-xl border p-4">
                <summary class="fuente-ui cursor-pointer font-semibold">¿Qué pasa si no puedo asistir a la fecha de examinación?</summary>
                <p class="mt-2">Podrás rendirla el último sábado de cada mes en la sede de La Molina, dentro de un plazo máximo de 2 meses.</p>
            </details>
            <details class="rounded-xl border p-4">
                <summary class="fuente-ui cursor-pointer font-semibold">¿Los precios incluyen IGV?</summary>
                <p class="mt-2">Sí, los precios publicados están en soles e incluyen IGV. La inscripción en línea se registra con el comprobante de pago y queda sujeta a aprobación. Si el curso no tiene precio publicado, se consulta por WhatsApp.</p>
            </details>
        </div>
    </section>

    <section id="contacto" class="bg-marca/5 py-14">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-2">
            <div>
                <h2 class="text-3xl text-marca">Contáctanos</h2>
                <p class="mt-3">{{ $marca->correo }}</p>
                <p>{{ $marca->telefono }}</p>
                <p>{{ $marca->direccion }}</p>
            </div>
            <form method="post" action="{{ route('contacto.guardar') }}" class="space-y-3">
                @csrf
                <input name="nombre" required placeholder="Nombre" class="w-full rounded-xl border bg-white px-4 py-3">
                <input name="correo" type="email" required placeholder="Correo" class="w-full rounded-xl border bg-white px-4 py-3">
                <textarea name="mensaje" required rows="4" placeholder="Cuéntanos qué necesita tu operación" class="w-full rounded-xl border bg-white px-4 py-3"></textarea>
                <button class="accion-principal fuente-ui rounded-lg bg-marca px-5 py-3 font-semibold text-white" type="submit">Enviar</button>
            </form>
        </div>
    </section>
@endsection
