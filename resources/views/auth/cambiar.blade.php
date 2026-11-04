@extends('layouts.panel')
@section('titulo', 'Cambiar contraseña')

@section('contenido')
    <h1 class="text-3xl text-marca">Cambiar contraseña</h1>
    <form method="post" action="{{ route('password.cambiar.guardar') }}" class="mt-6 max-w-md space-y-4">
        @csrf
        @method('PUT')
        <label class="block text-sm font-semibold" for="password_actual">Contraseña actual</label>
        <input id="password_actual" name="password_actual" type="password" required class="w-full rounded-xl border px-4 py-3">
        <label class="block text-sm font-semibold" for="password">Nueva contraseña</label>
        <input id="password" name="password" type="password" required class="w-full rounded-xl border px-4 py-3">
        <label class="block text-sm font-semibold" for="password_confirmation">Confirmar</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-xl border px-4 py-3">
        <button class="fuente-ui rounded-full bg-marca px-5 py-3 font-semibold text-white" type="submit">Actualizar</button>
    </form>
@endsection
