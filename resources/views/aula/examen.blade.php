@extends('layouts.panel')
@section('titulo', $examen->titulo)

@section('contenido')
    <div class="mx-auto max-w-5xl">
        <a class="fuente-ui text-sm font-semibold text-marca" href="{{ route('aula.curso', $examen->curso) }}">Volver al curso</a>
        <livewire:examenes.realizar-examen :examen="$examen" />
    </div>
@endsection