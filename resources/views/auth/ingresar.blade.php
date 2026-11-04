@extends('layouts.publico')
@section('titulo', 'Ingresar · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto max-w-md px-4 py-12">
        <h1 class="text-3xl text-marca">Ingresar</h1>
        <p class="mt-2 text-sm">Alumnos y docentes. Después de 3 intentos fallidos te pediremos recuperar o cambiar la contraseña.</p>
        @if (session('limite_login'))
            <div class="mt-4 rounded-xl border border-marca bg-marca/5 p-4">
                <p class="font-semibold">Tu acceso quedó en pausa por seguridad.</p>
                <a class="fuente-ui mt-3 inline-block rounded-full bg-marca px-4 py-2 text-sm font-semibold text-white" href="{{ route('password.request') }}">Recuperar o cambiar contraseña</a>
            </div>
        @endif
        <form method="post" action="{{ route('login.guardar') }}" class="mt-6 space-y-4">
            @csrf
            <label class="block text-sm font-semibold" for="email">Correo</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="password">Contraseña</label>
            <input id="password" name="password" type="password" required class="w-full rounded-xl border px-4 py-3">
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember" value="1"> Recordarme en este equipo</label>
            <button class="accion-principal fuente-ui w-full rounded-lg bg-marca py-3 font-semibold text-white max-lg:w-[calc(100%-4.5rem)]" type="submit">Ingresar</button>
        </form>
        <p class="mt-4 text-sm"><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></p>
        <p class="mt-2 text-sm">¿Aún no tienes cuenta? <a href="{{ route('registro') }}">Regístrate</a></p>
    </section>
@endsection
