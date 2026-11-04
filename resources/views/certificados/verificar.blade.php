@extends('layouts.publico')
@section('titulo', 'Verificar certificado · '.$marca->nombre_comercial)
@section('descripcion', 'Empleadores y alumnos pueden validar con el DNI, el CE o el código la autenticidad, la vigencia y los datos del curso.')

@section('contenido')
    <section class="bg-fondo py-12 text-white sm:py-14">
        <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full border border-white/15 bg-white/10 text-2xl text-acento"><i class="ti ti-shield-check" aria-hidden="true"></i></span>
            <h1 class="mt-4 text-3xl font-bold text-white sm:text-4xl">Verificación de certificados</h1>
            <p class="mx-auto mt-3 max-w-2xl leading-relaxed text-white/75">Consulta la autenticidad y vigencia de un certificado de C3 Perú con el DNI, CE o código impreso en el documento.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-12 sm:py-16">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <section class="rounded-xl border border-slate-200 bg-white p-5 sm:p-7">
                <h2 class="text-xl font-bold text-fondo">Consultar certificado</h2>
                <form method="post" action="{{ route('certificados.verificar') }}" class="mt-5 space-y-4">
                    @csrf
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="consulta">DNI, CE o código del certificado</label>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <input id="consulta" name="consulta" value="{{ old('consulta') }}" required maxlength="40" autocomplete="off" class="min-w-0 flex-1 rounded-lg border border-slate-300 px-4 py-3 font-mono focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20" aria-describedby="consulta-ayuda">
                            <button class="fuente-ui inline-flex items-center justify-center gap-2 rounded-lg bg-marca px-6 py-3 font-semibold text-white hover:bg-marca-2" type="submit">
                                <i class="ti ti-search" aria-hidden="true"></i> Verificar
                            </button>
                        </div>
                        <p id="consulta-ayuda" class="mt-2 text-xs text-fondo/60">También puedes usar el código QR para obtener el código del certificado.</p>
                        @error('consulta')
                            <p class="mt-2 text-sm text-red-700">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
            </section>

            @if ($consultado)
                <section class="mt-6 space-y-3" aria-live="polite" aria-label="Resultados de verificación">
                    @forelse ($resultados as $certificado)
                        <article class="rounded-xl border border-slate-200 bg-white p-5 sm:p-6">
                            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                                <div>
                                    <p class="fuente-ui text-xs font-semibold uppercase tracking-wide text-fondo/55">Resultado de verificación</p>
                                    <h2 class="mt-1 text-lg font-bold text-fondo">{{ $certificado->estado === 'vigente' ? 'Certificado auténtico y vigente' : 'Certificado encontrado' }}</h2>
                                </div>
                                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $certificado->estado === 'vigente' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-900' }}">{{ $certificado->estadoLegible() }}</span>
                            </div>

                            <dl class="mt-4 grid gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="fuente-ui text-xs font-semibold uppercase tracking-wide text-fondo/55">Participante</dt>
                                    <dd class="mt-1 font-semibold text-fondo">{{ $certificado->nombreEnmascarado() }}</dd>
                                </div>
                                <div>
                                    <dt class="fuente-ui text-xs font-semibold uppercase tracking-wide text-fondo/55">Fecha de emisión</dt>
                                    <dd class="mt-1 font-semibold text-fondo">{{ $certificado->emitido_en->timezone(config('app.timezone'))->format('d/m/Y') }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="fuente-ui text-xs font-semibold uppercase tracking-wide text-fondo/55">Curso</dt>
                                    <dd class="mt-1 font-semibold text-fondo">{{ $certificado->curso_titulo }}</dd>
                                </div>
                            </dl>
                        </article>
                    @empty
                        <div class="rounded-xl border border-slate-200 bg-white p-6" role="status">
                            <h2 class="font-bold text-fondo">No encontramos un certificado</h2>
                            <p class="mt-2 text-sm leading-relaxed text-fondo/70">Revisa el DNI, CE o código e inténtalo nuevamente. Si necesitas ayuda, <a class="font-semibold text-marca underline underline-offset-2" href="{{ route('contacto') }}">contáctanos</a>.</p>
                        </div>
                    @endforelse
                </section>
            @endif

            <p class="mt-6 text-xs leading-relaxed text-fondo/60">
                <i class="ti ti-lock mr-1" aria-hidden="true"></i>
                Por privacidad, mostramos el nombre parcialmente enmascarado y no exponemos números de documento. Los datos se utilizan únicamente para esta verificación.
            </p>
        </div>
    </section>
@endsection
