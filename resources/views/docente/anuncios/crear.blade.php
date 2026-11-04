@extends('layouts.panel')
@section('titulo', 'Crear anuncio · '.$curso->titulo)

@section('contenido')
    <div class="mx-auto max-w-3xl">
        <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('docente.anuncios.index', $curso) }}">← Volver al tablón</a>
        <div class="mt-3">
            <h1 class="text-3xl font-bold text-fondo">Crear anuncio</h1>
            <p class="mt-1 text-sm text-fondo/70">{{ $curso->titulo }} · {{ $alumnosActivos }} alumnos con matrícula activa.</p>
        </div>

        <form method="post" action="{{ route('docente.anuncios.guardar', $curso) }}" class="mt-6 space-y-5 rounded-2xl border border-slate-200 bg-white p-5 sm:p-7">
            @csrf
            <label class="block text-sm font-semibold text-fondo" for="titulo">Título del anuncio
                <input id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="180" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 font-normal focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20" placeholder="Ej.: Cambio de fecha para la clase práctica">
                @error('titulo')<span class="mt-1 block text-sm font-normal text-red-700">{{ $message }}</span>@enderror
            </label>

            <label class="block text-sm font-semibold text-fondo" for="contenido">Contenido
                <textarea id="contenido" name="contenido" rows="8" required maxlength="8000" class="mt-2 w-full rounded-lg border border-slate-300 px-4 py-3 font-normal focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20" placeholder="Escribe el mensaje para tus alumnos...">{{ old('contenido') }}</textarea>
                <span class="mt-1 block text-xs font-normal text-fondo/60">El anuncio se publicará en el curso y podrá enviarse por correo.</span>
                @error('contenido')<span class="mt-1 block text-sm font-normal text-red-700">{{ $message }}</span>@enderror
            </label>

            <div class="space-y-4 border-t border-slate-100 pt-5 text-sm">
                <label class="flex cursor-pointer items-start gap-3">
                    <input name="enviar_email" type="checkbox" value="1" @checked(old('enviar_email', true)) class="mt-1 rounded border-slate-300 text-marca focus:ring-marca">
                    <span><strong class="text-fondo">Enviar por correo</strong><span class="mt-0.5 block text-xs text-fondo/65">Los alumnos con matrícula activa recibirán el anuncio por correo.</span></span>
                </label>
                <label class="flex cursor-pointer items-start gap-3">
                    <input name="enviar_whatsapp" type="checkbox" value="1" @checked(old('enviar_whatsapp')) class="mt-1 rounded border-slate-300 text-marca focus:ring-marca">
                    <span><strong class="text-fondo">Preparar para compartir por WhatsApp</strong><span class="mt-0.5 block text-xs text-fondo/65">Después de publicar podrás abrir WhatsApp con el mensaje listo para compartir.</span></span>
                </label>
            </div>

            <div class="flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-5">
                <a class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-fondo hover:bg-slate-50" href="{{ route('docente.anuncios.index', $curso) }}">Cancelar</a>
                <button class="inline-flex items-center gap-2 rounded-lg bg-marca px-5 py-3 text-sm font-semibold text-white hover:bg-marca-2" type="submit">
                    <i class="ti ti-send" aria-hidden="true"></i> Publicar anuncio
                </button>
            </div>
        </form>
    </div>
@endsection
