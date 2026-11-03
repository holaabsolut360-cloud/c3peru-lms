@extends('layouts.publico')
@section('titulo', 'Contacto · '.$marca->nombre_comercial)

@section('contenido')
    <section class="mx-auto grid max-w-6xl gap-8 px-4 py-12 md:grid-cols-2">
        <div>
            <h1 class="text-4xl text-marca">Contacto</h1>
            <p class="mt-3">{{ $marca->correo }}</p>
            <p>{{ $marca->telefono }}</p>
            <p>{{ $marca->direccion }}</p>
            <p class="mt-2 text-sm">Gerencia general: {{ $marca->gerente_general }}</p>
        </div>
        <form method="post" action="{{ route('contacto.guardar') }}" class="space-y-3">
            @csrf
            <input name="nombre" value="{{ old('nombre') }}" required placeholder="Nombre" class="w-full rounded-xl border px-4 py-3">
            <input name="correo" type="email" value="{{ old('correo') }}" required placeholder="Correo" class="w-full rounded-xl border px-4 py-3">
            <input name="telefono" value="{{ old('telefono') }}" placeholder="Teléfono" class="w-full rounded-xl border px-4 py-3">
            <select name="especialidad" class="w-full rounded-xl border px-4 py-3">
                <option value="">Especialidad de interés</option>
                <option>Izaje y Manejo de Cargas</option>
                <option>Ensayos No Destructivos</option>
                <option>Confiabilidad y Técnicas de Mantenimiento</option>
                <option>Oil &amp; Gas</option>
            </select>
            <select name="modalidad" class="w-full rounded-xl border px-4 py-3">
                <option value="">Modalidad</option>
                <option>Programa semipresencial NDT 2026</option>
                <option>ADHOC</option>
                <option>INHOUSE</option>
                <option>E-Learning</option>
                <option>Online</option>
            </select>
            <textarea name="mensaje" required rows="5" placeholder="Cuéntanos qué necesita tu operación" class="w-full rounded-xl border px-4 py-3">{{ old('mensaje') }}</textarea>
            <button class="fuente-ui rounded-full bg-marca px-5 py-3 font-semibold text-white" type="submit">Enviar</button>
        </form>
    </section>
@endsection
