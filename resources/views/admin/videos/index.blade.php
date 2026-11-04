@extends('layouts.panel')
@section('titulo', 'Videos privados · '.$curso->titulo)

@section('contenido')
    <div class="mx-auto max-w-4xl">
        <a class="fuente-ui text-sm font-semibold text-marca hover:text-marca-2" href="{{ route('admin.cursos') }}">Volver a cursos</a>
        <h1 class="mt-3 text-3xl font-bold text-fondo">Videos privados</h1>
        <p class="mt-2 text-fondo/70">{{ $curso->titulo }}. Los videos se guardan fuera del directorio público y solo se sirven a alumnos con matrícula activa.</p>

        <section class="mt-6 rounded-xl border border-slate-200 bg-white p-5 sm:p-7">
            <h2 class="text-xl font-bold text-fondo">Cargar video a una lección</h2>
            <form method="post" action="{{ route('admin.cursos.videos.guardar', $curso) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
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
                        label="Archivo MP4 o WEBM (máx. 200 MB)"
                        accepted-file-types="video/mp4,video/webm"
                        required
                    />
                    <p class="mt-2 text-xs text-fondo/60">Arrastra el video aquí o selecciónalo. La validación final se aplica también al guardarlo.</p>
                    @error('video')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                </div>
                <button class="fuente-ui inline-flex items-center gap-2 rounded-lg bg-marca px-5 py-3 font-semibold text-white hover:bg-marca-2" type="submit">
                    <i class="ti ti-lock" aria-hidden="true"></i> Guardar en privado
                </button>
            </form>
        </section>

        <section class="mt-8 space-y-4">
            <h2 class="text-xl font-bold text-fondo">Videos cargados</h2>
            @foreach ($curso->lecciones as $leccion)
                <article class="rounded-lg border border-slate-200 bg-white p-4">
                    <h3 class="font-semibold text-marca">{{ $leccion->titulo }}</h3>
                    <ul class="mt-3 space-y-2">
                        @forelse ($leccion->videos as $video)
                            <li class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-3 text-sm">
                                <span>{{ $video->titulo }} · {{ strtoupper(str_replace('video/', '', $video->mime_type)) }} · Privado</span>
                                <form method="post" action="{{ route('admin.cursos.videos.eliminar', [$curso, $video]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="font-semibold text-red-700 hover:text-red-900" type="submit">Eliminar</button>
                                </form>
                            </li>
                        @empty
                            <li class="border-t border-slate-100 pt-3 text-sm text-fondo/60">Aún no hay videos para esta lección.</li>
                        @endforelse
                    </ul>
                </article>
            @endforeach
        </section>
    </div>
@endsection
