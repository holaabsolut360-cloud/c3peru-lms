@extends('layouts.panel')
@section('titulo', 'Clientes y aliados')

@section('contenido')
    <h1 class="text-3xl text-marca">Clientes, aliados y alianzas</h1>
    <p class="mt-2 text-sm">Lo que siga marcado POR CONFIRMAR se muestra así en la portada hasta que la empresa entregue el texto.</p>
    <form method="post" action="{{ route('admin.referencias.guardar') }}" class="mt-6 grid max-w-3xl gap-2 rounded-2xl border p-4">
        @csrf
        <h2 class="text-lg">Nueva referencia</h2>
        <select name="tipo" class="rounded-xl border px-3 py-2">
            <option value="testimonio">Testimonial</option>
            <option value="aliado">Aliado</option>
            <option value="alianza">Alianza</option>
            <option value="cliente">Cliente</option>
        </select>
        <input name="nombre" required placeholder="Nombre" class="rounded-xl border px-3 py-2">
        <input name="logo" placeholder="Ruta del logo, si ya está autorizado" class="rounded-xl border px-3 py-2">
        <input name="detalle" placeholder="Cargo, convenio o alcance" class="rounded-xl border px-3 py-2">
        <textarea name="texto" rows="3" placeholder="Texto" class="rounded-xl border px-3 py-2"></textarea>
        <input name="orden" value="1" class="rounded-xl border px-3 py-2">
        <label class="text-sm"><input type="checkbox" name="por_confirmar" value="1" checked> Marcar POR CONFIRMAR</label>
        <label class="text-sm"><input type="checkbox" name="publicado" value="1" checked> Publicar</label>
        <button class="w-fit rounded-lg bg-marca px-4 py-2 text-white" type="submit">Crear</button>
    </form>
    <div class="mt-6 space-y-4">
        @foreach ($referencias as $referencia)
            <form method="post" action="{{ route('admin.referencias.actualizar', $referencia) }}" class="grid max-w-3xl gap-2 rounded-2xl border p-4">
                @csrf
                @method('PUT')
                <select name="tipo" class="rounded-xl border px-3 py-2">
                    @foreach (['testimonio' => 'Testimonial', 'aliado' => 'Aliado', 'alianza' => 'Alianza', 'cliente' => 'Cliente'] as $valor => $etiqueta)
                        <option value="{{ $valor }}" @selected($referencia->tipo === $valor)>{{ $etiqueta }}</option>
                    @endforeach
                </select>
                <input name="nombre" value="{{ $referencia->nombre }}" class="rounded-xl border px-3 py-2">
                <input name="logo" value="{{ $referencia->logo }}" placeholder="Ruta del logo" class="rounded-xl border px-3 py-2">
                <input name="detalle" value="{{ $referencia->detalle }}" class="rounded-xl border px-3 py-2">
                <textarea name="texto" rows="3" class="rounded-xl border px-3 py-2">{{ $referencia->texto }}</textarea>
                <input name="orden" value="{{ $referencia->orden }}" class="rounded-xl border px-3 py-2">
                <label class="text-sm"><input type="checkbox" name="por_confirmar" value="1" @checked($referencia->por_confirmar)> POR CONFIRMAR</label>
                <label class="text-sm"><input type="checkbox" name="publicado" value="1" @checked($referencia->publicado)> Publicado</label>
                <div class="flex gap-3">
                    <button class="rounded-lg bg-marca px-4 py-2 text-white" type="submit">Guardar</button>
                </div>
            </form>
            <form method="post" action="{{ route('admin.referencias.eliminar', $referencia) }}" class="-mt-2 mb-4">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        @endforeach
    </div>
@endsection
