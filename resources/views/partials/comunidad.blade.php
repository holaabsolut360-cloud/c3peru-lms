<section id="comunidad" class="ficha-bloque bg-fondo text-left text-white">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="text-3xl font-extrabold md:text-4xl">Forma parte de nuestra comunidad</h2>
        <p class="mt-3 max-w-3xl text-white/80">{{ $marca->eslogan }} Capacitación y certificación con expertos, en aula, en línea o en tu unidad.</p>
        <div id="clientes" class="mt-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
            @forelse ($cifras as $cifra)
                <article class="tarjeta-suave bg-fondo-alt p-5 text-white">
                    <p class="text-2xl font-extrabold text-acento">{{ $cifra->valor }}</p>
                    <p class="mt-1 text-sm">{{ $cifra->etiqueta }}</p>
                </article>
            @empty
                <article class="tarjeta-suave col-span-2 bg-fondo-alt p-5 text-white lg:col-span-4">
                    <p class="text-sm font-semibold uppercase tracking-wide text-acento">POR CONFIRMAR</p>
                    <p class="mt-2">Las cifras de la comunidad (alumnos, cursos, horas y empresas) aún no están confirmadas. No publicamos números estimados.</p>
                </article>
            @endforelse
        </div>
    </div>
</section>

<section class="ficha-bloque fondo-suave text-left">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="text-3xl font-extrabold text-marca">Qué incluye la inscripción</h2>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <article class="tarjeta-suave p-5">
                <i class="ti ti-device-laptop text-2xl text-acento" aria-hidden="true"></i>
                <h3 class="mt-3 text-xl font-extrabold text-marca">E-learning</h3>
                <p class="mt-2 text-sm leading-6">Aprende a tu ritmo con clases teóricas y demostraciones en video y material interactivo.</p>
            </article>
            <article class="tarjeta-suave p-5">
                <i class="ti ti-tool text-2xl text-acento" aria-hidden="true"></i>
                <h3 class="mt-3 text-xl font-extrabold text-marca">Práctica</h3>
                <p class="mt-2 text-sm leading-6">Prácticas presenciales en Lima con equipos e insumos, o entrenamiento 100 % presencial si tu empresa lo requiere.</p>
            </article>
            <article class="tarjeta-suave p-5">
                <i class="ti ti-certificate text-2xl text-acento" aria-hidden="true"></i>
                <h3 class="mt-3 text-xl font-extrabold text-marca">Certificado verificable</h3>
                <p class="mt-2 text-sm leading-6">Empleadores y alumnos validan con el DNI la autenticidad, la vigencia y los datos: horas, fecha de emisión y código. También con el QR del certificado.</p>
            </article>
        </div>
    </div>
</section>

<section class="ficha-bloque bg-white text-left">
    <div class="mx-auto grid max-w-6xl gap-8 px-4 md:grid-cols-2">
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-acento">In company</p>
            <h2 class="mt-2 text-3xl font-extrabold text-marca">Capacita a tu equipo</h2>
            <p class="mt-4 text-lg">Entrenamos en tu unidad, con tus equipos.</p>
            <p class="mt-3 leading-7">Fechas, grupos y logística coordinados con tu equipo. Los programas se ajustan a los equipos y procedimientos de la operación.</p>
            <a class="fuente-ui mt-6 inline-block rounded-full bg-marca-2 px-7 py-3 font-semibold text-white hover:bg-marca" href="{{ route('contacto') }}">Contáctanos</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach (['Izajes y Manejo de Cargas', 'Ensayos No Destructivos', 'Técnicas de Mantenimiento', 'Oil & Gas'] as $especialidad)
                <article class="tarjeta-suave p-4">
                    <p class="font-semibold text-marca">{{ $especialidad }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<section class="ficha-bloque fondo-suave text-left">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="text-3xl font-extrabold text-marca">Aliados y clientes</h2>
        <p class="mt-2 max-w-3xl text-sm">Los logos y nombres se publican solo con autorización. Mientras no lleguen, cada espacio dice POR CONFIRMAR.</p>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach (collect($referencias['aliado'] ?? [])->concat($referencias['cliente'] ?? [])->concat($referencias['alianza'] ?? []) as $referencia)
                <article class="tarjeta-suave p-5">
                    @if ($referencia->por_confirmar)
                        <p class="text-xs font-semibold uppercase tracking-wide text-acento">POR CONFIRMAR</p>
                    @endif
                    @if ($referencia->logo)
                        <img src="{{ $referencia->logo }}" alt="{{ $referencia->nombre }}" class="mt-3 h-12 w-auto object-contain">
                    @else
                        <p class="mt-3 text-sm text-fondo/60">Logo POR CONFIRMAR</p>
                    @endif
                    <h3 class="mt-3 font-extrabold text-marca">{{ $referencia->nombre }}</h3>
                    @if ($referencia->texto)<p class="mt-2 text-sm leading-6">{{ $referencia->texto }}</p>@endif
                </article>
            @endforeach
        </div>
        @if (collect($referencias['testimonio'] ?? [])->isNotEmpty())
            <h3 class="mt-10 text-2xl font-extrabold text-marca">Testimonios</h3>
            <div class="mt-4 flex snap-x snap-mandatory gap-4 overflow-x-auto pb-2">
                @foreach ($referencias['testimonio'] as $testimonio)
                    <article class="tarjeta-suave w-[85%] shrink-0 snap-start p-5 sm:w-[calc(50%-0.5rem)]">
                        <p class="text-xs font-semibold uppercase tracking-wide text-acento">POR CONFIRMAR</p>
                        <p class="mt-3 text-sm leading-6">{{ $testimonio->texto }}</p>
                        <p class="mt-3 font-semibold text-marca">{{ $testimonio->nombre }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>

@if ($conCierre ?? false)
    <section class="ficha-bloque bg-white text-left">
        <div class="mx-auto max-w-3xl px-4">
            <h2 class="text-3xl font-extrabold text-marca">Preguntas frecuentes</h2>
            <div class="mt-4 space-y-3">
                <details class="tarjeta-suave p-4" open>
                    <summary class="cursor-pointer font-semibold text-marca">¿Pueden dictar el curso en nuestra empresa?</summary>
                    <p class="mt-3 text-sm leading-6">Sí. Entrenamos en tu unidad, con tus equipos. Con cursos inhouse el entrenamiento es 100 % presencial y el programa se ajusta a tus procedimientos.</p>
                </details>
                <details class="tarjeta-suave p-4">
                    <summary class="cursor-pointer font-semibold text-marca">¿Cómo se verifica un certificado?</summary>
                    <p class="mt-3 text-sm leading-6">Empleadores y alumnos validan con el DNI la autenticidad, la vigencia y los datos: horas, fecha de emisión y código. También puedes usar el QR del certificado.</p>
                </details>
            </div>
        </div>
    </section>
    <section class="ficha-bloque fondo-suave text-left">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-3xl font-extrabold text-marca">Contáctanos</h2>
            <p class="mt-3">{{ $marca->correo }} · {{ $marca->telefono }}</p>
            <p>{{ $marca->direccion }}</p>
            <a class="fuente-ui mt-6 inline-block rounded-full bg-marca-2 px-7 py-3 font-semibold text-white hover:bg-marca" href="{{ route('contacto') }}">Contáctanos</a>
        </div>
    </section>
@endif
