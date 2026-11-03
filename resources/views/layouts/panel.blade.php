<!DOCTYPE html>
<html lang="es-PE">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Panel') · {{ $marca->nombre_comercial }}</title>
    <meta name="robots" content="noindex">
    <link rel="icon" href="{{ $marca->favicon }}" type="image/png">
    <style>
        :root {
@foreach ($marca->variablesCss() as $nombre => $valor)
            {{ $nombre }}: {{ $valor }};
@endforeach
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white">
    <div class="flex min-h-screen">
        <aside class="fuente-ui hidden w-64 shrink-0 bg-fondo p-5 text-sm text-white md:block">
            <a href="{{ route('inicio') }}" class="block text-base font-semibold text-white">{{ $marca->nombre_comercial }}</a>
            <nav class="mt-6 space-y-2">
                @if (auth()->user()->esAdmin())
                    <a class="block text-white" href="{{ route('admin.inicio') }}">Resumen</a>
                    <a class="block text-white" href="{{ route('admin.pagos') }}">Pagos</a>
                    <a class="block text-white" href="{{ route('admin.certificados') }}">Certificados</a>
                    <a class="block text-white" href="{{ route('admin.usuarios') }}">Usuarios</a>
                    <a class="block text-white" href="{{ route('admin.reclamaciones') }}">Reclamaciones</a>
                    <a class="block text-white" href="{{ route('admin.apariencia') }}">Marca</a>
                @elseif (auth()->user()->esDocente())
                    <a class="block text-white" href="{{ route('docente.inicio') }}">Mis cursos</a>
                @else
                    <a class="block text-white" href="{{ route('aula.inicio') }}">Mis cursos</a>
                    <a class="block text-white" href="{{ route('aula.pagos') }}">Mis pagos</a>
                @endif
                <a class="block text-white" href="{{ route('password.cambiar') }}">Contraseña</a>
                <a class="block text-white" href="{{ route('cursos.index') }}">Catálogo</a>
            </nav>
            <form method="post" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button class="text-acento" type="submit">Salir</button>
            </form>
        </aside>
        <div class="flex-1">
            <header class="flex items-center justify-between border-b px-4 py-3 md:hidden">
                <a href="{{ auth()->user()->rutaInicio() }}">{{ $marca->nombre_comercial }}</a>
                <form method="post" action="{{ route('logout') }}">@csrf<button type="submit">Salir</button></form>
            </header>
            <main class="px-4 py-8 md:px-8">
                @if (session('estado'))
                    <p class="mb-4 rounded-lg bg-acento/15 px-4 py-3 text-marca">{{ session('estado') }}</p>
                @endif
                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-900">
                        <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif
                @yield('contenido')
            </main>
        </div>
    </div>
</body>
</html>
