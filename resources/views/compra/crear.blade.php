@extends('layouts.panel')
@section('titulo', 'Inscripción')

@section('contenido')
    <h1 class="text-3xl text-marca">Inscripción: {{ $curso->titulo }}</h1>
    <p class="mt-2">Monto: {{ $curso->precioFormateado() }} @if($curso->incluye_igv) (incluye IGV) @endif</p>
    @if ($yaMatriculado)
        <p class="mt-4">Ya estás matriculado. <a href="{{ route('aula.curso', $curso) }}">Ir al aula</a></p>
    @elseif ($pendiente)
        <p class="mt-4">Ya tenemos un comprobante pendiente de aprobación para este curso.</p>
    @else
        <form method="post" action="{{ route('compra.guardar', $curso) }}" enctype="multipart/form-data" class="mt-6 max-w-lg space-y-4">
            @csrf
            <label class="block text-sm font-semibold" for="referencia">Número de operación (opcional)</label>
            <input id="referencia" name="referencia" value="{{ old('referencia') }}" class="w-full rounded-xl border px-4 py-3">
            <label class="block text-sm font-semibold" for="comprobante">Comprobante (JPG, PNG, WEBP o PDF, máx. 5 MB)</label>
            <input id="comprobante" name="comprobante" type="file" required accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf">
            <button class="fuente-ui rounded-full bg-marca px-5 py-3 font-semibold text-white" type="submit">Enviar comprobante</button>
        </form>
        <p class="mt-4 text-sm">El archivo queda fuera del sitio público. Solo tú y administración pueden descargarlo.</p>
    @endif
@endsection
