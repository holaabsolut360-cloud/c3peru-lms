@extends('layouts.panel')
@section('titulo', 'Matrícula masiva')

@section('contenido')
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a class="text-sm font-semibold text-marca hover:underline" href="{{ route('admin.cursos') }}">← Volver a cursos</a>
            <p class="fuente-ui mt-5 text-sm font-semibold uppercase tracking-[0.16em] text-marca-2">Inscripción corporativa</p>
            <h1 class="mt-1 text-3xl text-fondo">Matrícula masiva</h1>
            <p class="mt-2 text-slate-600">Carga una lista de participantes para <span class="font-semibold">{{ $curso->titulo }}</span>.</p>
        </div>
        <span class="rounded-full bg-sky-50 px-3 py-1.5 text-xs font-semibold text-sky-800">XLSX, XLS o CSV · Máximo 10 MB</span>
    </div>

    @if ($resultado = session('matriculaMasiva'))
        <section class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-5" aria-label="Resultado de la importación">
            <h2 class="font-bold text-emerald-950">Importación procesada</h2>
            <dl class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div><dt class="text-xs text-emerald-800">Matrículas creadas</dt><dd class="mt-1 text-2xl font-bold text-emerald-950">{{ number_format($resultado['matriculas']) }}</dd></div>
                <div><dt class="text-xs text-emerald-800">Cuentas nuevas</dt><dd class="mt-1 text-2xl font-bold text-emerald-950">{{ number_format($resultado['usuarios_nuevos']) }}</dd></div>
                <div><dt class="text-xs text-emerald-800">Cuentas existentes</dt><dd class="mt-1 text-2xl font-bold text-emerald-950">{{ number_format($resultado['usuarios_existentes']) }}</dd></div>
                <div><dt class="text-xs text-emerald-800">Filas omitidas</dt><dd class="mt-1 text-2xl font-bold text-emerald-950">{{ number_format($resultado['omitidos']) }}</dd></div>
            </dl>

            @if ($resultado['errores'] !== [])
                <div class="mt-5 rounded-xl border border-amber-200 bg-white p-4">
                    <h3 class="text-sm font-bold text-amber-950">Revisa estas filas</h3>
                    <ul class="mt-2 space-y-1 text-sm text-slate-700">
                        @foreach ($resultado['errores'] as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @if ($resultado['cantidad_errores'] > count($resultado['errores']))
                        <p class="mt-2 text-xs text-slate-500">Se muestran las primeras {{ count($resultado['errores']) }} filas con problemas, de {{ $resultado['cantidad_errores'] }} en total.</p>
                    @endif
                </div>
            @else
                <p class="mt-4 text-sm text-emerald-800">Todas las filas válidas se procesaron correctamente.</p>
            @endif
        </section>
    @endif

    <div class="mt-6 grid gap-5 xl:grid-cols-5">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-3">
            <h2 class="text-lg font-bold text-fondo">Subir lista de participantes</h2>
            <p class="mt-1 text-sm text-slate-600">La fila 1 debe contener los nombres de las columnas.</p>

            <form method="post" action="{{ route('admin.cursos.matriculas.masiva.guardar', $curso) }}" enctype="multipart/form-data" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label for="archivo" class="mb-2 block text-sm font-semibold text-fondo">Archivo Excel o CSV</label>
                    <input
                        id="archivo"
                        name="archivo"
                        type="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-fondo hover:file:bg-slate-200"
                    >
                    <p class="mt-2 text-xs text-slate-500" data-archivo-seleccionado>Ningún archivo seleccionado.</p>
                    @error('archivo')
                        <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-marca px-5 py-3 text-sm font-semibold text-white transition hover:brightness-110">
                    <i class="ti ti-upload" aria-hidden="true"></i>
                    Procesar matrículas
                </button>
            </form>

            <div class="mt-6 rounded-xl bg-slate-50 p-4 text-sm text-slate-700">
                <p class="font-semibold text-fondo">Acceso de cuentas nuevas</p>
                <p class="mt-1">Se crea una clave aleatoria para cada cuenta nueva. Por seguridad, no se muestra ni se incluye en el archivo; el alumno podrá definir su clave desde “Recuperar contraseña”.</p>
            </div>
        </section>

        <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <h2 class="text-lg font-bold text-fondo">Columnas del archivo</h2>
            <p class="mt-1 text-sm text-slate-600">Usa una fila por participante y estos encabezados:</p>
            <ul class="mt-4 space-y-3 text-sm">
                <li><span class="font-semibold text-fondo">Obligatorias:</span> <code class="text-marca">documento</code> o <code class="text-marca">dni</code>, <code class="text-marca">nombres</code>, <code class="text-marca">apellidos</code> y <code class="text-marca">email</code>.</li>
                <li><span class="font-semibold text-fondo">Opcionales:</span> <code class="text-marca">documento_tipo</code> (DNI por defecto), <code class="text-marca">telefono</code> o <code class="text-marca">celular</code>, y <code class="text-marca">empresa</code>.</li>
            </ul>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full min-w-96 text-left text-xs">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr><th class="px-3 py-2 font-semibold">documento</th><th class="px-3 py-2 font-semibold">nombres</th><th class="px-3 py-2 font-semibold">apellidos</th><th class="px-3 py-2 font-semibold">email</th></tr>
                    </thead>
                    <tbody><tr class="text-slate-700"><td class="px-3 py-2">12345678</td><td class="px-3 py-2">Ana María</td><td class="px-3 py-2">Pérez Ruiz</td><td class="px-3 py-2">ana@empresa.pe</td></tr></tbody>
                </table>
            </div>
            <a href="{{ route('admin.cursos.matriculas.masiva.plantilla', $curso) }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-marca hover:underline">
                <i class="ti ti-download" aria-hidden="true"></i>
                Descargar plantilla de ejemplo
            </a>
            <p class="mt-4 text-xs leading-5 text-slate-500">Las filas vacías se ignoran. Si el documento o correo ya está asociado a otra cuenta, o el alumno ya está matriculado, esa fila se omite y se informa en el resultado.</p>
        </aside>
    </div>
@endsection
