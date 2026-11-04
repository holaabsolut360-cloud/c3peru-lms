@extends('layouts.publico')
@section('titulo', 'Registro · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto max-w-md px-4 py-12">
        <h1 class="text-3xl text-marca">Crear cuenta de alumno</h1>
        <p class="mt-2 text-sm">El DNI o CE queda guardado para emitir y verificar certificados. No publicamos el número completo.</p>
        <form method="post" action="{{ route('registro.guardar') }}" class="mt-6 space-y-4">
            @csrf
            <label class="block text-sm font-semibold" for="name">Nombre completo</label>
            <input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="email">Correo</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="documento_tipo">Documento</label>
            <select id="documento_tipo" name="documento_tipo" class="w-full rounded-xl border px-4 py-3">
                <option value="dni" @selected(old('documento_tipo') === 'dni')>DNI</option>
                <option value="ce" @selected(old('documento_tipo') === 'ce')>Carné de extranjería</option>
            </select>
            <label class="block text-sm font-semibold" for="documento_numero">Número</label>
            <input id="documento_numero" name="documento_numero" value="{{ old('documento_numero') }}" required class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="telefono">Celular (opcional)</label>
            <input id="telefono" name="telefono" value="{{ old('telefono') }}" class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="password">Contraseña</label>
            <input id="password" name="password" type="password" required class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-xl border px-4 py-3">
            <button class="fuente-ui w-full rounded-full bg-marca py-3 font-semibold text-white" type="submit">Crear cuenta</button>
        </form>
    </section>
@endsection
