@extends('layouts.publico')
@section('titulo', $marca->nombre_comercial.' · Capacitación y certificación')

@section('contenido')
    <section class="bg-fondo text-white">
        <div class="mx-auto grid max-w-6xl items-center gap-8 px-4 py-16 md:grid-cols-2">
            <div>
                <p class="fuente-ui text-acento">{{ $marca->lema }}</p>
                <h1 class="mt-3 text-4xl leading-tight md:text-5xl">Aprende haciendo, aprende con expertos</h1>
                <p class="mt-4 text-lg text-white/85">Capacitación y certificación de personas en Izaje y Manejo de Cargas, Ensayos No Destructivos, Oil &amp; Gas y Técnicas de Mantenimiento. Esquemas SNT-TC-1A, ISO 9712 e ISO 18436, con capacitadores ASNT NDT Level III.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a class="fuente-ui rounded-full bg-acento px-5 py-3 font-semibold text-fondo" href="{{ route('cursos.index') }}">Explorar cursos</a>
                    <a class="fuente-ui rounded-full border border-white/40 px-5 py-3 text-white" href="{{ route('certificados.formulario') }}">Verificar certificado</a>
                </div>
            </div>
            <img src="/marca/hero.jpg" alt="Capacitación industrial de C3 Perú" class="w-full rounded-3xl object-cover">
        </div>
    </section>

    <section class="mx-auto grid max-w-6xl gap-4 px-4 py-12 md:grid-cols-3">
        <article class="rounded-2xl bg-fondo-alt p-6 text-white">
            <h2 class="text-xl">Certificación de cursos</h2>
            <p class="mt-2 text-white/80">Constancia por participante. La entidad de acreditación específica de cada esquema queda por confirmar con la empresa.</p>
        </article>
        <article class="rounded-2xl bg-marca p-6 text-white">
            <h2 class="text-xl">In-company en mina</h2>
            <p class="mt-2 text-white/80">Entrenamos en tu unidad, con tus equipos.</p>
        </article>
        <article class="rounded-2xl bg-marca-2 p-6 text-white">
            <h2 class="text-xl">Instructores con experiencia</h2>
            <p class="mt-2 text-white/80">Especialistas del sector industrial. Las fotos de instructores están pendientes de confirmación.</p>
        </article>
    </section>

    <section class="mx-auto max-w-6xl px-4">
        <h2 class="text-3xl text-marca">Nuestros servicios</h2>
        <p class="mt-2 max-w-3xl">Somos un organismo de capacitación y certificación de personas. Los programas están liderados por profesionales de reconocida trayectoria. La frase pública de cumplimiento NTP ISO/IEC 17024 se mantiene como declaración del sitio; la acreditación formal está en la lista de pendientes.</p>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <a href="{{ route('paginas.ver', 'izajes-y-manejo-de-cargas') }}" class="rounded-2xl border p-5">
                <img src="/marca/izajes.jpg" alt="" class="mb-3 h-36 w-full rounded-xl object-cover">
                <h3 class="text-xl text-marca">Izaje y Manejo de Cargas</h3>
                <p class="text-sm">ASME B30, OSHAs, ISO. Inspección de aparejos, grúas móviles y grúas puente.</p>
            </a>
            <a href="{{ route('paginas.ver', 'ensayos-no-destructivos') }}" class="rounded-2xl border p-5">
                <img src="/marca/end.jpg" alt="" class="mb-3 h-36 w-full rounded-xl object-cover">
                <h3 class="text-xl text-marca">Ensayos No Destructivos</h3>
                <p class="text-sm">ASNT, ISO. VT, PT, MT, RT, UT, Phased Array y TOFD.</p>
            </a>
            <a href="{{ route('paginas.ver', 'oil-gas') }}" class="rounded-2xl border p-5">
                <img src="/marca/oilgas.jpg" alt="" class="mb-3 h-36 w-full rounded-xl object-cover">
                <h3 class="text-xl text-marca">Oil &amp; Gas</h3>
                <p class="text-sm">API y ASME. Pruebas de presión, tanques y preparación de exámenes API y CWI.</p>
            </a>
            <a href="{{ route('paginas.ver', 'tecnicas-de-mantenimiento') }}" class="rounded-2xl border p-5">
                <img src="/marca/mantenimiento.jpg" alt="" class="mb-3 h-36 w-full rounded-xl object-cover">
                <h3 class="text-xl text-marca">Confiabilidad y Técnicas de Mantenimiento</h3>
                <p class="text-sm">Termografía, alineamiento, balanceo, análisis vibracional y causa raíz.</p>
            </a>
        </div>
        <p class="mt-4 text-sm">Esquemas de certificación disponibles: END, SNT-TC-1A o ISO 9712. Termografía y análisis vibracional: SNT-TC-1A, ISO 9712 o ISO 18436, según aplique.</p>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl text-marca">Catálogo de cursos 2026</h2>
                <p>Cursos destacados del programa semipresencial NDT. Precios en soles, incluyen IGV.</p>
            </div>
            <a class="fuente-ui font-semibold" href="{{ route('cursos.index') }}">Explorar más cursos</a>
        </div>
        <div class="mt-6 grid gap-4 md:grid-cols-3">
            @forelse ($destacados as $curso)
                @include('partials.curso-card', ['curso' => $curso])
            @empty
                <p>El catálogo se publica al cargar la base de cursos.</p>
            @endforelse
        </div>
    </section>

    <section class="bg-fondo-alt py-12 text-white">
        <div class="mx-auto max-w-6xl px-4">
            <h2 class="text-3xl">Así funciona el modelo semipresencial</h2>
            <div class="mt-6 grid gap-4 md:grid-cols-4">
                <article><h3 class="text-acento">E-Learning</h3><p class="mt-2 text-sm text-white/80">Teoría, videos didácticos, plataforma con soporte y cuestionarios.</p></article>
                <article><h3 class="text-acento">Clase práctica</h3><p class="mt-2 text-sm text-white/80">Práctica presencial en Lima, con equipos e insumos, dictada por expertos.</p></article>
                <article><h3 class="text-acento">Examinación</h3><p class="mt-2 text-sm text-white/80">Evaluación de requisitos y exámenes presenciales: general, específico y práctico.</p></article>
                <article><h3 class="text-acento">Certificado verificable</h3><p class="mt-2 text-sm text-white/80">Valida la autenticidad con el DNI, el CE o el código en esta web.</p></article>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-12">
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
        <div class="mt-8 rounded-2xl bg-marca/5 p-6">
            <h2 class="text-2xl text-marca">Cotización corporativa</h2>
            <p class="mt-2">{{ $marca->correo }} · {{ $marca->telefono }} · {{ $marca->direccion }}</p>
            <a class="fuente-ui mt-4 inline-block font-semibold" href="{{ route('contacto') }}">Solicitar cotización</a>
        </div>
    </section>
@endsection
