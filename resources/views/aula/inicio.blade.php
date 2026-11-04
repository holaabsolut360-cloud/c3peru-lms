@extends('layouts.panel')
@section('titulo', 'Aula')

@section('contenido')
    <h1 class="text-3xl text-marca">Mis cursos</h1>
    <x-continuar-aprendiendo :ultima-actividad="$ultimaActividad" />
    <x-insignias-widget :insignias="$insignias" />
    @include('partials.proximas-clases-widget', ['clases' => $proximasClases])
    <div class="mt-6 grid gap-4 md:grid-cols-2">
        @forelse ($matriculas as $matricula)
            <article class="rounded-2xl border p-5">
                <h2 class="text-xl">{{ $matricula->curso->titulo }}</h2>
                <p class="text-sm text-marca-2">{{ $matricula->curso->categoria->nombre ?? '' }}</p>
                <a class="fuente-ui mt-3 inline-block font-semibold" href="{{ route('aula.curso', $matricula->curso) }}">Entrar al aula</a>
            </article>
        @empty
            <p>Todavía no tienes matrículas activas. Cuando administración apruebe tu comprobante, el curso aparecerá aquí.</p>
        @endforelse
    </div>
@endsection
