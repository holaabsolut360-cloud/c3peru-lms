@extends('layouts.panel')
@section('titulo', 'Contenido del curso · '.$curso->titulo)

@section('contenido')
    <div class="mx-auto max-w-4xl">
        <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('docente.inicio') }}">← Volver a mis cursos</a>
        <h1 class="mt-3 text-3xl font-bold text-fondo">Contenido del curso</h1>
        <p class="mt-2 text-fondo/70">{{ $curso->titulo }}. Los videos se guardan en privado y solo se sirven a alumnos con matrícula activa.</p>

        <section class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 sm:p-7">
            <h2 class="text-xl font-bold text-fondo">Cargar video a una lección</h2>
            @if ($lecciones->isEmpty())
                <p class="mt-4 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">Este curso todavía no tiene lecciones. Pide al administrador que las configure antes de cargar videos.</p>
            @else
                <form method="post" action="{{ route('docente.contenido.guardar', $curso) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="leccion_id">Lección</label>
                        <select id="leccion_id" name="leccion_id" required class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3">
                            @foreach ($lecciones as $leccion)
                                <option value="{{ $leccion->id }}" @selected((string) old('leccion_id') === (string) $leccion->id)>{{ $leccion->titulo }}</option>
                            @endforeach
                        </select>
                        @error('leccion_id')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="titulo">Título del video</label>
                        <input id="titulo" name="titulo" value="{{ old('titulo') }}" required maxlength="180" class="w-full rounded-lg border border-slate-300 px-4 py-3">
                        @error('titulo')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <x-filepond-upload
                            id="video"
                            name="video"
                            label="Arrastra aquí el video o selecciónalo (MP4 o WEBM, máximo 200 MB)"
                            accepted-file-types="video/mp4,video/webm"
                            required
                        />
                        @error('video')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div class="border-t border-slate-100 pt-4">
                        <x-filepond-upload
                            name="materiales[]"
                            label="Material complementario (PDF, Office, imágenes o ZIP)"
                            accepted-file-types="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,image/jpeg,image/png,application/zip"
                            :multiple="true"
                        />
                        <p class="mt-2 text-xs text-fondo/60">Hasta 10 archivos de 50 MB cada uno. Se almacenan en privado y quedan asociados a la lección elegida.</p>
                        @error('materiales')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <button class="inline-flex items-center gap-2 rounded-lg bg-marca px-5 py-3 font-semibold text-white hover:bg-marca-2" type="submit">
                        <i class="ti ti-lock" aria-hidden="true"></i> Guardar contenido privado
                    </button>
                </form>
            @endif
        </section>

        <section class="mt-8 space-y-4">
            <h2 class="text-xl font-bold text-fondo">Videos de las lecciones</h2>
            @foreach ($curso->lecciones as $leccion)
                <article class="rounded-xl border border-slate-200 bg-white p-5">
                    <h3 class="font-semibold text-marca">{{ $leccion->titulo }}</h3>
                    <ul class="mt-3 space-y-2">
                        @forelse ($leccion->videos as $video)
                            <li class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3 text-sm">
                                <span>{{ $video->titulo }} · {{ strtoupper(str_replace('video/', '', $video->mime_type)) }} · Privado</span>
                                <form method="post" action="{{ route('docente.contenido.videos.eliminar', [$curso, $video]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-semibold text-red-700 hover:text-red-900" type="submit">Eliminar</button>
                                </form>
                            </li>
                        @empty
                            <li class="border-t border-slate-100 pt-3 text-sm text-fondo/60">Todavía no hay videos para esta lección.</li>
                        @endforelse
                    </ul>
                    @if ($leccion->materiales->isNotEmpty())
                        <h4 class="mt-4 border-t border-slate-100 pt-3 text-sm font-semibold text-fondo">Material complementario</h4>
                        <ul class="mt-2 space-y-2">
                            @foreach ($leccion->materiales as $material)
                                <li class="flex flex-wrap items-center justify-between gap-3 text-sm">
                                    <span>{{ $material->nombre_original }} · {{ number_format($material->tamano_bytes / 1048576, 1) }} MB</span>
                                    <form method="post" action="{{ route('docente.contenido.materiales.eliminar', [$curso, $material]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="font-semibold text-red-700 hover:text-red-900" type="submit">Eliminar</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </article>
            @endforeach
        </section>
    </div>
@endsection
