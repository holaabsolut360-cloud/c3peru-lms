@extends('layouts.panel')
@section('titulo', 'Inscripción: '.$curso->titulo)
@section('contexto-whatsapp', 'inscripcion')

@section('contenido')
    <div class="mx-auto max-w-4xl">
        <header class="mb-8 max-w-2xl">
            <p class="fuente-ui text-sm font-semibold uppercase tracking-wider text-marca">Inscripción al curso</p>
            <h1 class="mt-2 text-3xl font-bold text-fondo sm:text-4xl">Completa tu inscripción</h1>
            <p class="mt-3 leading-relaxed text-fondo/70">Adjunta el comprobante de pago. Administración revisará la operación antes de activar tu matrícula.</p>
        </header>

        <ol class="mb-8 grid grid-cols-3 items-start gap-2 text-center" aria-label="Progreso de inscripción">
            <li class="flex flex-col items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-marca text-sm font-bold text-white"><i class="ti ti-check" aria-hidden="true"></i></span>
                <span class="fuente-ui text-xs font-semibold text-fondo">Tu cuenta</span>
            </li>
            <li class="flex flex-col items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-full {{ $pendiente || $yaMatriculado ? 'bg-marca text-white' : 'bg-acento text-fondo' }} text-sm font-bold">
                    @if ($pendiente || $yaMatriculado)<i class="ti ti-check" aria-hidden="true"></i>@else 2 @endif
                </span>
                <span class="fuente-ui text-xs font-semibold text-fondo">Comprobante</span>
            </li>
            <li class="flex flex-col items-center gap-2">
                <span class="flex h-10 w-10 items-center justify-center rounded-full {{ $yaMatriculado ? 'bg-marca text-white' : 'bg-slate-200 text-slate-500' }} text-sm font-bold">
                    @if ($yaMatriculado)<i class="ti ti-check" aria-hidden="true"></i>@else 3 @endif
                </span>
                <span class="fuente-ui text-xs font-semibold text-fondo">Validación</span>
            </li>
        </ol>

        <section class="mb-6 flex flex-col gap-4 rounded-xl border border-slate-200 bg-white p-4 sm:flex-row sm:items-center">
            <img src="{{ $curso->imagenPortada() }}" alt="{{ $curso->titulo }}" class="h-24 w-full rounded-lg object-cover sm:w-36">
            <div class="min-w-0 flex-1">
                <p class="fuente-ui text-xs font-semibold uppercase tracking-wide text-marca">{{ $curso->categoria?->nombre ?? 'Curso' }}</p>
                <h2 class="mt-1 text-lg font-bold text-fondo">{{ $curso->titulo }}</h2>
                <p class="mt-1 text-sm text-fondo/65">{{ $curso->duracion ?: 'Duración por confirmar' }}@if($curso->modalidad) · {{ $curso->modalidad }}@endif</p>
            </div>
            <div class="sm:text-right">
                <span class="block text-xs text-fondo/60">{{ $curso->incluye_igv ? 'Incluye IGV' : 'Monto' }}</span>
                <span class="text-xl font-bold text-fondo">{{ $curso->precioFormateado() }}</span>
            </div>
        </section>

        @if ($yaMatriculado)
            <section class="rounded-xl border border-emerald-200 bg-emerald-50 p-6 text-emerald-950">
                <h2 class="text-lg font-bold">Tu matrícula está activa</h2>
                <p class="mt-2 text-sm">Ya tienes acceso al curso.</p>
                <a class="fuente-ui mt-4 inline-flex items-center gap-2 rounded-lg bg-marca px-5 py-3 font-semibold text-white hover:bg-marca-2" href="{{ route('aula.curso', $curso) }}">
                    Ir al aula <i class="ti ti-arrow-right" aria-hidden="true"></i>
                </a>
            </section>
        @elseif ($pendiente)
            <section class="rounded-xl border border-amber-200 bg-amber-50 p-6 text-amber-950">
                <h2 class="text-lg font-bold">Comprobante recibido</h2>
                <p class="mt-2 text-sm">Tu comprobante está pendiente de revisión por administración. Te avisaremos cuando cambie el estado del pago.</p>
            </section>
        @else
            <section class="rounded-xl border border-slate-200 bg-white p-5 sm:p-7">
                <div class="mb-6 rounded-lg border border-acento/40 bg-acento/10 p-4">
                    <div class="flex items-start gap-3">
                        <i class="ti ti-info-circle mt-0.5 text-xl text-marca" aria-hidden="true"></i>
                        <div class="text-sm text-fondo/80">
                            <p class="font-semibold text-fondo">Confirma los datos de pago antes de transferir.</p>
                            <p class="mt-1">Si aún no los tienes, solicítalos al equipo de C3 Perú.</p>
                            <a class="fuente-ui mt-2 inline-flex items-center gap-1 font-semibold text-marca underline underline-offset-2" href="{{ $marca->enlaceWhatsapp('Hola, quiero inscribirme en '.$curso->titulo.'. ¿Me comparten los datos de pago?') }}" target="_blank" rel="noopener">
                                Consultar por WhatsApp <i class="ti ti-external-link" aria-hidden="true"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <form method="post" action="{{ route('compra.guardar', $curso) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <h2 class="text-lg font-bold text-fondo">Comprobante de pago</h2>
                        <p class="mt-1 text-sm text-fondo/65">Formatos JPG, PNG, WEBP o PDF. Tamaño máximo: 5 MB.</p>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="comprobante">Selecciona tu comprobante <span class="text-red-700">*</span></label>
                        <input id="comprobante" name="comprobante" type="file" required accept=".jpg,.jpeg,.png,.webp,.pdf,image/jpeg,image/png,image/webp,application/pdf" aria-describedby="comprobante-ayuda" class="block w-full cursor-pointer rounded-lg border border-slate-300 bg-white text-sm text-fondo file:mr-4 file:border-0 file:bg-marca file:px-4 file:py-3 file:font-semibold file:text-white hover:file:bg-marca-2">
                        <p id="comprobante-ayuda" class="mt-2 text-xs text-fondo/60">El archivo se almacena de forma privada y solo puede ser consultado por ti y administración.</p>
                        @error('comprobante')
                            <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="referencia">Número de operación <span class="font-normal text-fondo/60">(opcional)</span></label>
                        <input id="referencia" name="referencia" value="{{ old('referencia') }}" maxlength="80" autocomplete="off" class="w-full rounded-lg border border-slate-300 px-4 py-3 text-fondo focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                        @error('referencia')
                            <p class="mt-2 text-sm font-medium text-red-700">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="fuente-ui inline-flex w-full items-center justify-center gap-2 rounded-lg bg-marca px-5 py-3.5 font-semibold text-white transition-colors hover:bg-marca-2 sm:w-auto" type="submit">
                        <i class="ti ti-send" aria-hidden="true"></i> Enviar comprobante
                    </button>
                </form>
            </section>
        @endif
    </div>
@endsection
