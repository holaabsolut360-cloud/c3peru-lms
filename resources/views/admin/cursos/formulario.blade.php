@extends('layouts.panel')
@section('titulo', $curso->exists ? 'Editar curso' : 'Nuevo curso')

@section('contenido')
    <h1 class="text-3xl text-marca">{{ $curso->exists ? 'Editar curso' : 'Nuevo curso' }}</h1>
    <form method="post" enctype="multipart/form-data" action="{{ $curso->exists ? route('admin.cursos.actualizar', $curso) : route('admin.cursos.guardar') }}" class="mt-6 grid max-w-3xl gap-3">
        @csrf
        @if ($curso->exists) @method('PUT') @endif
        <label class="text-sm">Categoría
            <select name="categoria_id" class="mt-1 w-full rounded-xl border px-3 py-2">
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}" @selected(old('categoria_id', $curso->categoria_id) == $categoria->id)>{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </label>
        <label class="text-sm">Título <input name="titulo" value="{{ old('titulo', $curso->titulo) }}" required class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Nivel <input name="nivel" value="{{ old('nivel', $curso->nivel) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Duración <input name="duracion" value="{{ old('duracion', $curso->duracion) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Modalidad <input name="modalidad" value="{{ old('modalidad', $curso->modalidad) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Fechas <input name="fechas" value="{{ old('fechas', $curso->fechas) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Certificación <input name="certificacion" value="{{ old('certificacion', $curso->certificacion) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Precio en soles (vacío = Consultar por WhatsApp) <input name="precio" value="{{ old('precio', $curso->precio) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Orden <input name="orden" value="{{ old('orden', $curso->orden) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Descripción <textarea name="descripcion" rows="4" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('descripcion', $curso->descripcion) }}</textarea></label>
        <label class="text-sm">Frase de beneficio <textarea name="beneficio" rows="2" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('beneficio', $curso->beneficio) }}</textarea></label>
        <label class="text-sm">Aprendizajes, uno por línea <textarea name="aprendizajes" rows="4" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('aprendizajes', $curso->textoLineas('aprendizajes')) }}</textarea></label>
        <label class="text-sm">Temario: una línea por módulo, «título || descripción» <textarea name="temario" rows="5" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('temario', $curso->textoLineas('temario')) }}</textarea></label>
        <label class="text-sm">A quién va dirigido <textarea name="dirigido_a" rows="3" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('dirigido_a', $curso->dirigido_a) }}</textarea></label>
        <label class="text-sm">Requisitos <textarea name="requisitos" rows="3" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('requisitos', $curso->requisitos) }}</textarea></label>
        <label class="text-sm">Horario <input name="horario" value="{{ old('horario', $curso->horario) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Fecha de inicio <input name="fecha_inicio" value="{{ old('fecha_inicio', $curso->fecha_inicio) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Qué incluye, uno por línea <textarea name="incluye" rows="3" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('incluye', $curso->textoLineas('incluye')) }}</textarea></label>
        <label class="text-sm">Preguntas: «pregunta || respuesta» por línea <textarea name="preguntas" rows="4" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('preguntas', $curso->textoLineas('preguntas')) }}</textarea></label>
        <label class="text-sm">Testimonios: «nombre || texto» por línea. Márcalos POR CONFIRMAR si aún no son reales. <textarea name="testimonios" rows="4" class="mt-1 w-full rounded-xl border px-3 py-2">{{ old('testimonios', $curso->textoLineas('testimonios')) }}</textarea></label>
        <label class="text-sm">Instructor <input name="instructor_nombre" value="{{ old('instructor_nombre', $curso->instructor_nombre) }}" placeholder="Nombre o POR CONFIRMAR" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Cargo del instructor <input name="instructor_cargo" value="{{ old('instructor_cargo', $curso->instructor_cargo) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Enlace del instructor <input name="instructor_enlace" value="{{ old('instructor_enlace', $curso->instructor_enlace) }}" class="mt-1 w-full rounded-xl border px-3 py-2"></label>
        <label class="text-sm">Foto del instructor
            <input name="instructor_foto" type="file" accept="image/jpeg,image/png,image/webp" class="mt-2 block">
        </label>
        <label class="text-sm">PDF del temario
            <input name="temario_pdf" type="file" accept="application/pdf" class="mt-2 block">
        </label>
        <label class="text-sm">Portada (JPG, PNG o WEBP)
            @if ($curso->exists)<img src="{{ $curso->imagenPortada() }}" alt="" class="mt-2 h-24 rounded-lg object-cover">@endif
            <input name="portada" type="file" accept="image/jpeg,image/png,image/webp" class="mt-2 block">
        </label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="publicado" value="1" @checked(old('publicado', $curso->publicado))> Publicado</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="incluye_igv" value="1" @checked(old('incluye_igv', $curso->incluye_igv))> El precio incluye IGV</label>
        <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="destacado" value="1" @checked(old('destacado', $curso->destacado))> Destacado en la portada</label>
        <button class="w-fit rounded-lg bg-marca px-5 py-3 font-semibold text-white" type="submit">Guardar curso</button>
    </form>
@endsection
