<section id="comunidad" class="ficha-bloque bg-fondo text-left text-white">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="text-3xl font-extrabold md:text-5xl">Forma parte de nuestra comunidad</h2>
        <p class="mt-3 max-w-3xl text-white/80">{{ $marca->eslogan }} Capacitación y certificación con expertos, en aula, en línea o en tu unidad.</p>
        <div id="clientes" class="mt-8 grid min-w-0 grid-cols-2 gap-4 lg:grid-cols-4">
            @forelse ($cifras as $cifra)
                @php
                    $numero = preg_match('/^\d+$/', trim((string) $cifra->valor));
                    $icono = match ($cifra->etiqueta) {
                        'Alumnos certificados' => 'ti-users',
                        'Cursos' => 'ti-books',
                        'Horas de formación' => 'ti-clock-hour-4',
                        'Empresas atendidas' => 'ti-building-factory-2',
                        default => 'ti-chart-bar',
                    };
                @endphp
                <article class="min-w-0 rounded-2xl border border-white/10 bg-fondo-alt p-5">
                    <i class="ti {{ $icono }} text-4xl text-acento" aria-hidden="true"></i>
                    <p class="mt-3 break-words font-extrabold leading-tight text-acento {{ $numero ? 'text-4xl md:text-5xl' : 'text-xl sm:text-2xl' }}" @if ($numero) data-contar="{{ $cifra->valor }}" @endif>{{ $numero ? '0' : $cifra->valor }}</p>
                    <p class="mt-1 text-sm">{{ $cifra->etiqueta }}</p>
                </article>
            @empty
                <article class="col-span-2 rounded-2xl border border-white/10 bg-fondo-alt p-5 lg:col-span-4">
                    <p class="text-sm font-semibold uppercase tracking-wide text-acento">POR CONFIRMAR</p>
                    <p class="mt-2">Las cifras de la comunidad aún no están confirmadas. No publicamos números estimados.</p>
                </article>
            @endforelse
        </div>
    </div>
</section>

<section class="ficha-bloque fondo-suave text-left">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="text-3xl font-extrabold text-marca">Qué incluye la inscripción</h2>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            <article class="tarjeta-suave p-6">
                <i class="ti ti-device-laptop text-5xl text-acento" aria-hidden="true"></i>
                <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-acento">A tu ritmo</p>
                <h3 class="mt-1 text-xl font-extrabold text-marca">E-learning</h3>
                <p class="mt-3 text-sm leading-6">Aprende a tu ritmo con clases teóricas y demostraciones en video y material interactivo.</p>
            </article>
            <article class="tarjeta-suave p-6">
                <i class="ti ti-tool text-5xl text-acento" aria-hidden="true"></i>
                <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-acento">Con equipos</p>
                <h3 class="mt-1 text-xl font-extrabold text-marca">Práctica</h3>
                <p class="mt-3 text-sm leading-6">Prácticas presenciales en Lima con equipos e insumos, o entrenamiento 100 % presencial si tu empresa lo requiere.</p>
            </article>
            <article class="tarjeta-suave p-6">
                <i class="ti ti-certificate text-5xl text-acento" aria-hidden="true"></i>
                <p class="mt-4 text-xs font-semibold uppercase tracking-wide text-acento">Verificable</p>
                <h3 class="mt-1 text-xl font-extrabold text-marca">Certificado</h3>
                <p class="mt-3 text-sm leading-6">Empleadores y alumnos validan con el DNI la autenticidad, la vigencia y los datos: horas, fecha de emisión y código. También con el QR del certificado.</p>
            </article>
        </div>
    </div>
</section>

<section class="ficha-bloque bg-white text-left">
    <div class="mx-auto max-w-6xl px-4">
    <div class="relative overflow-hidden rounded-3xl">
        <img src="/marca/mantenimiento.webp" width="1400" height="934" alt="Trabajo de mantenimiento en planta" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
        <div class="absolute inset-0 bg-fondo/70"></div>
        <div class="relative grid gap-6 p-8 text-white md:grid-cols-2 md:p-12">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-acento">In company</p>
                <h2 class="mt-2 text-3xl font-extrabold text-white">Capacita a tu equipo</h2>
                <p class="mt-4 text-lg">Entrenamos en tu unidad, con tus equipos.</p>
            </div>
            <p class="leading-7 text-white/90">Fechas, grupos y logística coordinados con tu equipo. Los programas se ajustan a los equipos y procedimientos de la operación.</p>
        </div>
    </div>
    </div>
</section>

<section class="ficha-bloque fondo-suave text-left">
    <div class="mx-auto max-w-6xl px-4">
        <h2 class="text-3xl font-extrabold text-marca">Aliados y clientes</h2>
        <p class="mt-2 max-w-3xl text-sm">Los logos y nombres se publican solo con autorización.</p>
        <div class="mt-6 flex w-full min-w-0 snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth pb-2">
            @php $marcas = collect($referencias['aliado'] ?? [])->concat($referencias['cliente'] ?? [])->concat($referencias['alianza'] ?? []); @endphp
            @forelse ($marcas as $referencia)
                <article class="tarjeta-suave flex w-64 shrink-0 snap-start flex-col justify-between p-5">
                    @if ($referencia->por_confirmar)
                        <p class="text-xs font-semibold uppercase tracking-wide text-acento">POR CONFIRMAR</p>
                    @endif
                    @if ($referencia->logo)
                        <img src="{{ $referencia->logo }}" alt="Logo de {{ $referencia->nombre }}" class="mt-3 h-12 w-auto object-contain" loading="lazy">
                    @else
                        <p class="mt-6 text-sm text-fondo/60">Logo POR CONFIRMAR</p>
                    @endif
                    <h3 class="mt-4 font-extrabold text-marca">{{ $referencia->nombre }}</h3>
                </article>
            @empty
                <p class="tarjeta-suave p-5">POR CONFIRMAR. Aún no hay aliados ni clientes autorizados.</p>
            @endforelse
        </div>

        <h3 class="mt-10 text-2xl font-extrabold text-marca">Testimonios</h3>
        <div class="mt-4 flex w-full min-w-0 snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth pb-2">
            @forelse ($referencias['testimonio'] ?? [] as $testimonio)
                <article class="tarjeta-suave w-72 max-w-full shrink-0 snap-start p-5 sm:w-96">
                    @if ($testimonio->por_confirmar)
                        <p class="text-xs font-semibold uppercase tracking-wide text-acento">POR CONFIRMAR</p>
                    @endif
                    <p class="mt-3 text-sm leading-6">{{ $testimonio->texto }}</p>
                    <p class="mt-4 font-semibold text-marca">{{ $testimonio->nombre }}</p>
                </article>
            @empty
                <p class="tarjeta-suave w-full p-5">POR CONFIRMAR. Aún no hay testimonios autorizados.</p>
            @endforelse
        </div>
    </div>
</section>

@if ($conCierre ?? false)
    <section class="px-4 pb-16">
        <div class="relative mx-auto max-w-6xl min-h-80 overflow-hidden rounded-3xl">
            <img src="/marca/oilgas.webp" width="1400" height="1867" alt="Instalación industrial de oil y gas al atardecer" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
            <div class="absolute inset-0 bg-fondo/75"></div>
            <div class="relative max-w-xl p-8 text-white md:p-12">
                <h2 class="text-3xl font-extrabold text-white md:text-4xl">Contáctanos</h2>
                <p class="mt-4">{{ $marca->correo }}</p>
                <p>{{ $marca->telefono }}</p>
                <p class="mt-2 text-white/80">{{ $marca->direccion }}</p>
                <a class="fuente-ui mt-6 inline-block rounded-full bg-acento px-7 py-3 font-semibold text-fondo" href="{{ route('contacto') }}">Contáctanos</a>
            </div>
        </div>
    </section>
@endif
