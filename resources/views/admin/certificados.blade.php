@extends('layouts.panel')
@section('titulo', 'Certificados')

@section('contenido')
    <h1 class="text-3xl text-marca">Certificados</h1>
    <p class="mt-2 text-sm">El código usa el prefijo {{ $marca->prefijo_certificado }}. El PDF y la verificación leen la marca guardada.</p>
    <div class="mt-6 space-y-3">
        @foreach ($matriculas as $matricula)
            <article class="rounded-2xl border p-4 text-sm">
                <p class="font-semibold">{{ $matricula->usuario->name }} · {{ $matricula->curso->titulo }}</p>
                <p>Documento: {{ $matricula->usuario->documento_tipo }} {{ $matricula->usuario->documento_numero }}</p>
                @if ($matricula->certificado)
                    <p>Código {{ $matricula->certificado->codigo }} · {{ $matricula->certificado->estadoLegible() }}</p>
                    <a href="{{ route('certificados.pdf', $matricula->certificado) }}">Descargar PDF</a>
                    @if ($matricula->certificado->estado === 'vigente')
                        <form method="post" action="{{ route('admin.certificados.revocar', $matricula->certificado) }}" class="inline">
                            @csrf
                            <button type="submit">Revocar</button>
                        </form>
                    @endif
                @else
                    <form method="post" action="{{ route('admin.certificados.emitir', $matricula) }}">
                        @csrf
                        <button class="rounded-full bg-marca px-4 py-2 text-white" type="submit">Emitir certificado</button>
                    </form>
                @endif
            </article>
        @endforeach
    </div>
@endsection
