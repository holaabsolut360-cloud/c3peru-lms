@extends('layouts.publico')
@section('titulo', 'Libro de reclamaciones · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto max-w-3xl px-4 py-12">
        <h1 class="text-4xl text-marca">Libro de reclamaciones</h1>
        <p class="mt-3">{{ $marca->razon_social }} · RUC {{ $marca->ruc }} · {{ $marca->direccion }}</p>
        @if (session('codigo_reclamo'))
            <p class="mt-4 rounded-xl bg-acento/15 p-4">Registramos tu hoja con el código <strong>{{ session('codigo_reclamo') }}</strong>. El proveedor responderá en un plazo no mayor de quince días calendario, que puede ampliarse quince días más.</p>
        @endif
        <form method="post" action="{{ route('reclamaciones.guardar') }}" class="mt-6 grid gap-3 md:grid-cols-2">
            @csrf
            <input name="nombre" value="{{ old('nombre') }}" required placeholder="Nombre" class="rounded-xl border px-4 py-3">
            <input name="primer_apellido" value="{{ old('primer_apellido') }}" required placeholder="Primer apellido" class="rounded-xl border px-4 py-3">
            <input name="segundo_apellido" value="{{ old('segundo_apellido') }}" placeholder="Segundo apellido" class="rounded-xl border px-4 py-3 md:col-span-2">
            <select name="tipo_documento" class="rounded-xl border px-4 py-3">
                @foreach (['dni' => 'DNI', 'ce' => 'CE', 'pasaporte' => 'Pasaporte', 'ruc' => 'RUC'] as $valor => $etiqueta)
                    <option value="{{ $valor }}" @selected(old('tipo_documento') === $valor)>{{ $etiqueta }}</option>
                @endforeach
            </select>
            <input name="numero_documento" value="{{ old('numero_documento') }}" required placeholder="Número de documento" class="rounded-xl border px-4 py-3">
            <input name="celular" value="{{ old('celular') }}" required placeholder="Celular" class="rounded-xl border px-4 py-3">
            <input name="correo" type="email" value="{{ old('correo') }}" required placeholder="Correo" class="rounded-xl border px-4 py-3">
            <select name="departamento" required class="rounded-xl border px-4 py-3">
                <option value="">Departamento</option>
                @foreach ($departamentos as $departamento)
                    <option @selected(old('departamento') === $departamento)>{{ $departamento }}</option>
                @endforeach
            </select>
            <input name="provincia" value="{{ old('provincia') }}" required placeholder="Provincia" class="rounded-xl border px-4 py-3">
            <input name="distrito" value="{{ old('distrito') }}" required placeholder="Distrito" class="rounded-xl border px-4 py-3">
            <input name="direccion" value="{{ old('direccion') }}" required placeholder="Dirección" class="rounded-xl border px-4 py-3 md:col-span-2">
            <input name="referencia" value="{{ old('referencia') }}" placeholder="Referencia" class="rounded-xl border px-4 py-3 md:col-span-2">
            <label class="text-sm">¿Eres menor de edad?
                <select name="es_menor" class="mt-1 w-full rounded-xl border px-4 py-3">
                    <option value="0" @selected(old('es_menor', '0') === '0')>No</option>
                    <option value="1" @selected(old('es_menor') === '1')>Sí</option>
                </select>
            </label>
            <input name="tutor_nombre" value="{{ old('tutor_nombre') }}" placeholder="Nombre del padre, madre o tutor" class="rounded-xl border px-4 py-3">
            <input name="tutor_correo" value="{{ old('tutor_correo') }}" placeholder="Correo del tutor" class="rounded-xl border px-4 py-3">
            <select name="tutor_tipo_documento" class="rounded-xl border px-4 py-3">
                <option value="">Documento del tutor</option>
                @foreach (['dni' => 'DNI', 'ce' => 'CE', 'pasaporte' => 'Pasaporte', 'ruc' => 'RUC'] as $valor => $etiqueta)
                    <option value="{{ $valor }}">{{ $etiqueta }}</option>
                @endforeach
            </select>
            <input name="tutor_numero_documento" value="{{ old('tutor_numero_documento') }}" placeholder="Número del tutor" class="rounded-xl border px-4 py-3">
            <select name="tipo" required class="rounded-xl border px-4 py-3">
                <option value="reclamacion">Reclamación: desacuerdo con productos o servicios</option>
                <option value="queja">Queja: malestar con la atención u otro desacuerdo</option>
            </select>
            <select name="tipo_consumo" required class="rounded-xl border px-4 py-3">
                <option value="servicio">Servicio</option>
                <option value="producto">Producto</option>
            </select>
            <input name="numero_pedido" value="{{ old('numero_pedido') }}" required placeholder="N.º de pedido" class="rounded-xl border px-4 py-3">
            <label class="text-sm">Fecha de reclamación
                <input type="date" name="fecha_reclamo" value="{{ old('fecha_reclamo', now()->toDateString()) }}" required class="mt-1 w-full rounded-xl border px-4 py-3">
            </label>
            <input name="proveedor" value="{{ old('proveedor', $marca->razon_social) }}" placeholder="Proveedor" class="rounded-xl border px-4 py-3">
            <input name="monto" value="{{ old('monto') }}" placeholder="Monto reclamado (S/)" class="rounded-xl border px-4 py-3">
            <textarea name="descripcion_bien" required placeholder="Descripción del producto o servicio" class="rounded-xl border px-4 py-3 md:col-span-2">{{ old('descripcion_bien') }}</textarea>
            <label class="text-sm">Fecha de compra <input type="date" name="fecha_compra" value="{{ old('fecha_compra') }}" class="mt-1 w-full rounded-xl border px-4 py-3"></label>
            <label class="text-sm">Fecha de consumo <input type="date" name="fecha_consumo" value="{{ old('fecha_consumo') }}" class="mt-1 w-full rounded-xl border px-4 py-3"></label>
            <label class="text-sm md:col-span-2">Fecha de caducidad <input type="date" name="fecha_caducidad" value="{{ old('fecha_caducidad') }}" class="mt-1 w-full rounded-xl border px-4 py-3"></label>
            <textarea name="detalle" required placeholder="Detalle de la reclamación o queja" class="rounded-xl border px-4 py-3 md:col-span-2">{{ old('detalle') }}</textarea>
            <textarea name="pedido_cliente" required placeholder="Pedido del cliente" class="rounded-xl border px-4 py-3 md:col-span-2">{{ old('pedido_cliente') }}</textarea>
            <label class="flex items-start gap-2 text-sm md:col-span-2">
                <input type="checkbox" name="acepta_privacidad" value="1" required>
                <span>Declaro ser titular del servicio y acepto el contenido de este formulario bajo declaración jurada. He leído la política de privacidad y la de cookies. La formulación del reclamo no impide acudir a Indecopi.</span>
            </label>
            <button class="fuente-ui rounded-full bg-marca px-5 py-3 font-semibold text-white md:col-span-2" type="submit">Enviar hoja de reclamación</button>
        </form>
    </section>
@endsection
