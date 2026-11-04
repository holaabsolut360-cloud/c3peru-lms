@extends('layouts.panel')
@section('titulo', 'Comunidad')

@section('contenido')
    <h1 class="text-3xl text-marca">Comunidad y equipo</h1>
    <p class="mt-2 text-sm">No pongas cifras estimadas. Si el dato no está confirmado, deja el valor en POR CONFIRMAR.</p>

    <h2 class="mt-8 text-xl font-extrabold text-marca">Cifras</h2>
    <form method="post" action="{{ route('admin.comunidad.cifras') }}" class="mt-3 flex flex-wrap gap-2">
        @csrf
        <input name="etiqueta" required placeholder="Etiqueta" class="rounded-xl border px-3 py-2">
        <input name="valor" required placeholder="Valor o POR CONFIRMAR" class="rounded-xl border px-3 py-2">
        <input name="orden" value="1" class="w-20 rounded-xl border px-3 py-2">
        <label class="text-sm"><input type="checkbox" name="publicado" value="1" checked> Publicar</label>
        <button class="rounded-full bg-marca px-4 py-2 text-white" type="submit">Agregar</button>
    </form>
    @foreach ($cifras as $cifra)
        <form method="post" action="{{ route('admin.comunidad.cifras.actualizar', $cifra) }}" class="mt-3 flex flex-wrap gap-2">
            @csrf @method('PUT')
            <input name="etiqueta" value="{{ $cifra->etiqueta }}" class="rounded-xl border px-3 py-2">
            <input name="valor" value="{{ $cifra->valor }}" class="rounded-xl border px-3 py-2">
            <input name="orden" value="{{ $cifra->orden }}" class="w-20 rounded-xl border px-3 py-2">
            <label class="text-sm"><input type="checkbox" name="publicado" value="1" @checked($cifra->publicado)> Publicar</label>
            <button class="rounded-full bg-marca-2 px-4 py-2 text-white" type="submit">Guardar</button>
        </form>
    @endforeach

    <h2 class="mt-10 text-xl font-extrabold text-marca">Compromiso</h2>
    @foreach ($compromisos as $compromiso)
        <form method="post" action="{{ route('admin.comunidad.compromisos.actualizar', $compromiso) }}" class="mt-3 grid max-w-3xl gap-2">
            @csrf @method('PUT')
            <input name="titulo" value="{{ $compromiso->titulo }}" class="rounded-xl border px-3 py-2">
            <textarea name="texto" rows="4" class="rounded-xl border px-3 py-2">{{ $compromiso->texto }}</textarea>
            <input name="orden" value="{{ $compromiso->orden }}" class="w-20 rounded-xl border px-3 py-2">
            <label class="text-sm"><input type="checkbox" name="propuesto" value="1" @checked($compromiso->propuesto)> Texto propuesto, por confirmar con el cliente</label>
            <label class="text-sm"><input type="checkbox" name="publicado" value="1" @checked($compromiso->publicado)> Publicar</label>
            <button class="w-fit rounded-full bg-marca px-4 py-2 text-white" type="submit">Guardar compromiso</button>
        </form>
    @endforeach

    <h2 class="mt-10 text-xl font-extrabold text-marca">Instructores</h2>
    @foreach ($instructores as $persona)
        <form method="post" enctype="multipart/form-data" action="{{ route('admin.comunidad.instructores.actualizar', $persona) }}" class="mt-4 grid max-w-3xl gap-2 rounded-2xl border p-4">
            @csrf @method('PUT')
            <input name="nombre" value="{{ $persona->nombre }}" class="rounded-xl border px-3 py-2">
            <input name="cargo" value="{{ $persona->cargo }}" class="rounded-xl border px-3 py-2">
            <input name="iniciales" value="{{ $persona->iniciales }}" class="w-24 rounded-xl border px-3 py-2">
            <input name="linkedin" value="{{ $persona->linkedin }}" class="rounded-xl border px-3 py-2">
            <textarea name="bio" rows="4" class="rounded-xl border px-3 py-2">{{ $persona->bio }}</textarea>
            <textarea name="credenciales" rows="3" placeholder="Una credencial real por línea" class="rounded-xl border px-3 py-2">{{ implode("\n", $persona->credenciales ?? []) }}</textarea>
            <input name="orden" value="{{ $persona->orden }}" class="w-20 rounded-xl border px-3 py-2">
            <input name="foto" type="file" accept="image/jpeg,image/png,image/webp">
            <label class="text-sm"><input type="checkbox" name="publicado" value="1" @checked($persona->publicado)> Publicar</label>
            <button class="w-fit rounded-full bg-marca px-4 py-2 text-white" type="submit">Guardar instructor</button>
        </form>
    @endforeach
@endsection
