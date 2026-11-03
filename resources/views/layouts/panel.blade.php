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
        <aside class="fuente-ui hidden w-64 shrink-0 bg-fondo p-5 text-white md:block">
            <a href="{{ route('inicio') }}" class="block text-base font-semibold text-white">{{ $marca->nombre_comercial }}</a>
            <div class="mt-6">@include('partials.menu-panel')</div>
            <form method="post" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button class="text-acento" type="submit">Salir</button>
            </form>
        </aside>
        <div class="flex-1">
            <header class="border-b bg-fondo text-white md:hidden">
                <div class="flex items-center justify-between px-4 py-3">
                    <a class="text-white" href="{{ auth()->user()->rutaInicio() }}">{{ $marca->nombre_comercial }}</a>
                    <form method="post" action="{{ route('logout') }}">@csrf<button class="text-acento" type="submit">Salir</button></form>
                </div>
                <details class="border-t border-white/10 px-4 py-2">
                    <summary class="cursor-pointer list-none py-1 font-semibold [&::-webkit-details-marker]:hidden">Menú del panel</summary>
                    <div class="pb-3 pt-2">@include('partials.menu-panel')</div>
                </details>
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
