@extends('layouts.panel')
@section('titulo', 'Usuarios')

@section('contenido')
    <h1 class="text-3xl text-marca">Usuarios</h1>
    <form method="post" action="{{ route('admin.usuarios.docentes') }}" class="mt-6 grid max-w-xl gap-2">
        @csrf
        <h2 class="text-xl">Nuevo docente</h2>
        <input name="name" required placeholder="Nombre" class="rounded-xl border px-3 py-2">
        <input name="email" type="email" required placeholder="Correo" class="rounded-xl border px-3 py-2">
        <input name="password" type="text" required placeholder="Contraseña inicial" class="rounded-xl border px-3 py-2">
        <button class="rounded-full bg-marca px-4 py-2 text-white" type="submit">Crear docente</button>
    </form>
    <div class="mt-8 space-y-3">
        @foreach ($usuarios as $usuario)
            <article class="rounded-2xl border p-4 text-sm">
                <p class="font-semibold">{{ $usuario->name }} · {{ $usuario->role }}</p>
                <p>{{ $usuario->email }}</p>
                <form method="post" action="{{ route('admin.usuarios.documento', $usuario) }}" class="mt-2 flex flex-wrap gap-2">
                    @csrf
                    @method('PUT')
                    <select name="documento_tipo" class="rounded-xl border px-2 py-1">
                        <option value="dni" @selected($usuario->documento_tipo === 'dni')>DNI</option>
                        <option value="ce" @selected($usuario->documento_tipo === 'ce')>CE</option>
                    </select>
                    <input name="documento_numero" value="{{ $usuario->documento_numero }}" placeholder="Número" class="rounded-xl border px-2 py-1">
                    <button type="submit">Guardar documento</button>
                </form>
            </article>
        @endforeach
    </div>
@endsection
