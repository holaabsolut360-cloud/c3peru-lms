@php
    $enlace = $movil
        ? 'block rounded-lg px-3 py-2 text-white hover:bg-white/10'
        : 'text-white';
    $boton = $movil
        ? 'mt-2 block rounded-lg bg-acento px-3 py-2 text-center font-semibold text-fondo'
        : 'rounded-lg bg-acento px-4 py-2 font-semibold text-fondo';
@endphp
<div class="{{ $movil ? 'flex flex-col' : 'flex items-center gap-4' }}">
    @if ($movil)
        <details>
            <summary class="{{ $enlace }} cursor-pointer list-none [&::-webkit-details-marker]:hidden">Nosotros</summary>
            <div class="ml-3 border-l border-white/15 pl-3">
                <a class="{{ $enlace }}" href="{{ route('paginas.ver', 'nosotros') }}">Nuestro Compromiso</a>
                <a class="{{ $enlace }}" href="{{ route('paginas.ver', 'equipo') }}">Equipo</a>
                <a class="{{ $enlace }}" href="{{ route('paginas.ver', 'nosotros') }}#vision">Visión + Misión</a>
            </div>
        </details>
    @else
        <div class="group relative">
            <a class="{{ $enlace }}" href="{{ route('paginas.ver', 'nosotros') }}">Nosotros</a>
            <div class="menu-desplegable absolute left-0 top-full z-40 w-64 rounded-xl bg-fondo p-2 pt-2 shadow-xl">
                <a class="block rounded-lg px-3 py-2 text-white hover:bg-white/10" href="{{ route('paginas.ver', 'nosotros') }}">Nuestro Compromiso</a>
                <a class="block rounded-lg px-3 py-2 text-white hover:bg-white/10" href="{{ route('paginas.ver', 'equipo') }}">Equipo</a>
                <a class="block rounded-lg px-3 py-2 text-white hover:bg-white/10" href="{{ route('paginas.ver', 'nosotros') }}#vision">Visión + Misión</a>
            </div>
        </div>
    @endif
    <a class="{{ $enlace }}" href="{{ route('cursos.index') }}">Cursos</a>
    <a class="{{ $enlace }}" href="{{ route('certificados.formulario') }}">Certificados</a>
    <a class="{{ $enlace }}" href="{{ route('blog') }}">Blog</a>
    <a class="{{ $enlace }}" href="{{ route('contacto') }}">Contáctanos</a>
    @auth
        <a class="{{ $boton }}" href="{{ auth()->user()->rutaInicio() }}">Mi espacio</a>
    @else
        <a class="{{ $boton }}" href="{{ route('login') }}">Iniciar Sesión</a>
    @endauth
</div>
