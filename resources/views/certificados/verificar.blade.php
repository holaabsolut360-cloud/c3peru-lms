@extends('layouts.publico')
@section('titulo', 'Verificar certificado · '.$marca->nombre_comercial)
@section('descripcion', 'Empleadores y alumnos pueden validar con el DNI, el CE o el código la autenticidad, la vigencia y los datos del curso.')

@section('contenido')
    <section class="mx-auto max-w-xl px-4 py-12">
        <h1 class="text-4xl text-marca">Verifica un certificado</h1>
        <p class="mt-3">¿El certificado es auténtico y está vigente? Empleadores y alumnos pueden validar con el DNI, el CE o el código del certificado. También puedes leer el código QR impreso en el PDF.</p>
        <p class="mt-3 text-sm">Datos mostrados conforme a la Ley N.° 29733 de Protección de Datos Personales, con consentimiento del titular, únicamente para verificar la autenticidad del certificado.</p>
        <form method="post" action="{{ route('certificados.verificar') }}" class="mt-6 space-y-3">
            @csrf
            <label class="block fuente-ui text-sm font-semibold" for="consulta">DNI, CE o código del certificado</label>
            <input id="consulta" name="consulta" value="{{ old('consulta') }}" required maxlength="40" class="w-full rounded-xl border px-4 py-3" autocomplete="off">
            <button class="fuente-ui rounded-full bg-marca px-5 py-3 font-semibold text-white" type="submit">Verificar</button>
        </form>

        @if ($consultado)
            <div class="mt-8 space-y-3">
                @forelse ($resultados as $certificado)
                    <article class="rounded-2xl border p-4">
                        <p><span class="font-semibold">Nombre:</span> {{ $certificado->nombreEnmascarado() }}</p>
                        <p><span class="font-semibold">Curso:</span> {{ $certificado->curso_titulo }}</p>
                        <p><span class="font-semibold">Fecha:</span> {{ $certificado->emitido_en->timezone(config('app.timezone'))->format('d/m/Y') }}</p>
                        <p><span class="font-semibold">Estado:</span> {{ $certificado->estadoLegible() }}</p>
                    </article>
                @empty
                    <p class="rounded-xl bg-marca/5 p-4">No encontramos un certificado con esos datos.</p>
                @endforelse
            </div>
        @endif
    </section>
@endsection
