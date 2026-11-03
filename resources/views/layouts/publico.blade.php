<!DOCTYPE html>
<html lang="es-PE">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', $marca->nombre_comercial)</title>
    <meta name="description" content="@yield('descripcion', $marca->meta_descripcion)">
    <meta property="og:locale" content="es_PE">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $marca->nombre_comercial }}">
    <meta property="og:title" content="@yield('titulo', $marca->nombre_comercial)">
    <meta property="og:description" content="@yield('descripcion', $marca->meta_descripcion)">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url($marca->logo) }}">
    <link rel="icon" href="{{ $marca->favicon }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ $marca->apple_touch }}">
    <style>
        :root {
@foreach ($marca->variablesCss() as $nombre => $valor)
            {{ $nombre }}: {{ $valor }};
@endforeach
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white text-fondo">
    <div class="bg-fondo text-white text-sm">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-2 px-4 py-2">
            <p>{{ $marca->telefono }} · {{ $marca->correo }} · La Molina, Perú</p>
            <p class="fuente-ui">{{ $marca->lema }}</p>
        </div>
    </div>
    <header class="sticky top-0 z-30 border-b border-white/10 bg-fondo-alt text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-4 py-3">
            <a href="{{ route('inicio') }}" class="flex items-center gap-3 text-white">
                <img src="{{ $marca->logo }}" alt="{{ $marca->nombre_comercial }}" class="h-12 w-auto bg-white/95 px-2 py-1">
            </a>
            <nav class="fuente-ui hidden items-center gap-4 text-sm lg:flex">
                <a class="text-white" href="{{ route('inicio') }}">Inicio</a>
                <a class="text-white" href="{{ route('paginas.ver', 'nosotros') }}">Nosotros</a>
                <a class="text-white" href="{{ route('cursos.index') }}">Cursos</a>
                <a class="text-white" href="{{ route('paginas.ver', 'clientes') }}">Clientes</a>
                <a class="text-white" href="{{ route('blog') }}">Blog</a>
                <a class="text-white" href="{{ route('contacto') }}">Contacto</a>
                <a class="text-white" href="{{ route('certificados.formulario') }}">Verificar</a>
                @auth
                    <a class="rounded-full bg-acento px-3 py-1 text-fondo" href="{{ auth()->user()->rutaInicio() }}">Mi espacio</a>
                @else
                    <a class="text-white" href="{{ route('login') }}">Ingresar</a>
                    <a class="rounded-full bg-acento px-3 py-1 text-fondo" href="{{ route('registro') }}">Regístrate</a>
                @endauth
            </nav>
        </div>
        <nav class="fuente-ui flex gap-3 overflow-x-auto px-4 pb-3 text-sm lg:hidden">
            <a class="text-white" href="{{ route('cursos.index') }}">Cursos</a>
            <a class="text-white" href="{{ route('certificados.formulario') }}">Verificar</a>
            <a class="text-white" href="{{ route('contacto') }}">Contacto</a>
            @guest
                <a class="text-white" href="{{ route('login') }}">Ingresar</a>
            @endguest
        </nav>
    </header>

    <main>
        @if (session('estado'))
            <div class="mx-auto mt-4 max-w-6xl rounded-lg bg-acento/15 px-4 py-3 text-marca">{{ session('estado') }}</div>
        @endif
        @if ($errors->any())
            <div class="mx-auto mt-4 max-w-6xl rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-900">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('contenido')
    </main>

    <footer class="mt-16 bg-fondo text-white">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 py-12 md:grid-cols-4">
            <div>
                <img src="{{ $marca->logo }}" alt="" class="mb-3 h-12 bg-white/95 px-2 py-1">
                <p class="text-sm text-white/80">{{ $marca->texto_footer }}</p>
            </div>
            <div>
                <h2 class="text-base text-acento">Especialidades</h2>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a class="text-white" href="{{ route('paginas.ver', 'izajes-y-manejo-de-cargas') }}">Izaje y Manejo de Cargas</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'ensayos-no-destructivos') }}">Ensayos No Destructivos</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'tecnicas-de-mantenimiento') }}">Técnicas de Mantenimiento</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'oil-gas') }}">Oil &amp; Gas</a></li>
                </ul>
            </div>
            <div>
                <h2 class="text-base text-acento">Empresa</h2>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a class="text-white" href="{{ route('paginas.ver', 'nosotros') }}">Nosotros</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'equipo') }}">Equipo</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'modalidades-de-entrenamiento') }}">Modalidades</a></li>
                    <li><a class="text-white" href="{{ route('contacto') }}">Contacto</a></li>
                    <li><a class="text-white" href="mailto:{{ $marca->correo }}">{{ $marca->correo }}</a></li>
                </ul>
            </div>
            <div>
                <h2 class="text-base text-acento">Legal</h2>
                <ul class="mt-3 space-y-2 text-sm">
                    <li><a class="text-white" href="{{ route('paginas.ver', 'terminos-y-condiciones') }}">Términos y condiciones</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'politica-de-privacidad') }}">Política de privacidad</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'politica-de-cookies') }}">Política de cookies</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'aviso-legal') }}">Aviso legal</a></li>
                    <li><a class="text-white" href="{{ route('paginas.ver', 'devoluciones-y-reembolso') }}">Devoluciones y reembolso</a></li>
                    <li><a class="text-white" href="{{ route('reclamaciones.crear') }}">Libro de reclamaciones</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-3 px-4 py-4 text-sm text-white/80">
                <p>{{ $marca->razon_social }} · RUC {{ $marca->ruc }}</p>
                <p>{{ $marca->direccion }}</p>
                <p>{{ $marca->copyright }}</p>
                <div class="flex gap-3 text-lg">
                    <a class="text-white" href="{{ $marca->linkedin }}" aria-label="LinkedIn"><i class="ti ti-brand-linkedin"></i></a>
                    <a class="text-white" href="{{ $marca->youtube }}" aria-label="YouTube"><i class="ti ti-brand-youtube"></i></a>
                    <a class="text-white" href="{{ $marca->instagram }}" aria-label="Instagram"><i class="ti ti-brand-instagram"></i></a>
                    <a class="text-white" href="{{ $marca->facebook }}" aria-label="Facebook"><i class="ti ti-brand-facebook"></i></a>
                    <a class="text-white" href="{{ $marca->tiktok }}" aria-label="TikTok"><i class="ti ti-brand-tiktok"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <a class="boton-whatsapp fixed bottom-5 right-5 z-40 flex items-center gap-2 rounded-full px-4 py-3 text-sm font-semibold shadow-lg"
       href="{{ $marca->enlaceWhatsapp('Hola, quiero información sobre los cursos de C3 Perú.') }}"
       target="_blank" rel="noopener">
        <i class="ti ti-brand-whatsapp text-xl"></i>
        WhatsApp
    </a>
</body>
</html>
