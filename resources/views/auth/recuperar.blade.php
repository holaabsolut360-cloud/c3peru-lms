@extends('layouts.publico')
@section('titulo', 'Recuperar contraseña · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto max-w-md px-4 py-12">
        <h1 class="text-3xl text-marca">Recuperar o cambiar contraseña</h1>
        <p class="mt-2">Escribe el correo de tu cuenta de alumno o docente. Si está registrado, te enviaremos un enlace. No indicamos si el correo existe.</p>
        <form method="post" action="{{ route('password.email') }}" class="mt-6 space-y-4">
            @csrf
            <label class="block text-sm font-semibold" for="email">Correo</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border px-4 py-3">
            <button class="fuente-ui w-full rounded-full bg-marca py-3 font-semibold text-white" type="submit">Enviar instrucciones</button>
        </form>
    </section>
@endsection
