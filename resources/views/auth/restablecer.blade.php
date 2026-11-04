@extends('layouts.publico')
@section('titulo', 'Nueva contraseña · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto max-w-md px-4 py-12">
        <h1 class="text-3xl text-marca">Elige una contraseña nueva</h1>
        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label class="block text-sm font-semibold" for="email">Correo</label>
            <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="password">Nueva contraseña</label>
            <input id="password" name="password" type="password" required class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="password_confirmation">Confirmar</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-xl border px-4 py-3">
            <button class="fuente-ui w-full rounded-full bg-marca py-3 font-semibold text-white" type="submit">Guardar contraseña</button>
        </form>
    </section>
@endsection
