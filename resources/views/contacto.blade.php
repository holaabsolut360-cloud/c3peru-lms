@extends('layouts.publico')
@section('titulo', 'Contacto y cotización · '.$marca->nombre_comercial)
@section('contexto-whatsapp', 'cotizacion')
@section('descripcion', 'Solicita una propuesta de capacitación para tu equipo en Izaje, Ensayos No Destructivos, Oil & Gas o Mantenimiento.')

@section('contenido')
    <section class="bg-fondo py-14 text-white sm:py-16">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            <p class="fuente-ui text-sm font-semibold uppercase tracking-wider text-acento">Capacitación para empresas</p>
            <h1 class="mt-3 max-w-3xl text-3xl font-bold text-white sm:text-4xl">Contacto y cotización corporativa</h1>
            <p class="mt-4 max-w-2xl leading-relaxed text-white/75">Cuéntanos qué necesita tu operación. Revisaremos los requerimientos de tu equipo y nos pondremos en contacto contigo.</p>
        </div>
    </section>

    <section class="bg-slate-50 py-12 sm:py-16">
        <div class="mx-auto grid max-w-6xl gap-8 px-4 sm:px-6 lg:grid-cols-3 lg:px-8">
            <aside class="space-y-8">
                <div>
                    <h2 class="text-xl font-bold text-fondo">Información de contacto</h2>
                    <dl class="mt-5 space-y-5">
                        <div class="flex items-start gap-3">
                            <i class="ti ti-mail mt-0.5 text-xl text-marca" aria-hidden="true"></i>
                            <div><dt class="fuente-ui text-xs font-semibold uppercase tracking-wide text-fondo/55">Correo</dt><dd class="mt-1"><a class="font-semibold text-marca hover:text-marca-2" href="mailto:{{ $marca->correo }}">{{ $marca->correo }}</a></dd></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="ti ti-brand-whatsapp mt-0.5 text-xl text-emerald-600" aria-hidden="true"></i>
                            <div><dt class="fuente-ui text-xs font-semibold uppercase tracking-wide text-fondo/55">Teléfono / WhatsApp</dt><dd class="mt-1"><a class="font-semibold text-marca hover:text-marca-2" href="{{ $marca->enlaceWhatsapp('Hola, quiero cotizar una capacitación para mi empresa.') }}" target="_blank" rel="noopener">{{ $marca->telefono }}</a></dd></div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="ti ti-map-pin mt-0.5 text-xl text-marca" aria-hidden="true"></i>
                            <div><dt class="fuente-ui text-xs font-semibold uppercase tracking-wide text-fondo/55">Dirección</dt><dd class="mt-1 font-medium text-fondo">{{ $marca->direccion }}</dd></div>
                        </div>
                    </dl>
                </div>

                <div class="border-t border-slate-200 pt-6">
                    <h2 class="text-lg font-bold text-fondo">Capacitación según tu operación</h2>
                    <ul class="mt-4 space-y-3 text-sm text-fondo/75">
                        <li class="flex items-start gap-2"><i class="ti ti-check text-marca" aria-hidden="true"></i><span>Programas en Izaje, Ensayos No Destructivos, Oil &amp; Gas y Mantenimiento.</span></li>
                        <li class="flex items-start gap-2"><i class="ti ti-check text-marca" aria-hidden="true"></i><span>Alternativas presenciales, virtuales o semipresenciales, según el curso.</span></li>
                        <li class="flex items-start gap-2"><i class="ti ti-check text-marca" aria-hidden="true"></i><span>Coordinación de grupos, fechas y necesidades del equipo.</span></li>
                    </ul>
                </div>
            </aside>

            <section class="rounded-xl border border-slate-200 bg-white p-5 sm:p-7 lg:col-span-2">
                <h2 class="text-2xl font-bold text-fondo">Solicitar una cotización</h2>
                <p class="mt-2 text-sm leading-relaxed text-fondo/65">Completa tus datos y agrega los detalles que ayudarán a preparar la propuesta.</p>

                <form method="post" action="{{ route('contacto.guardar') }}" class="mt-7 space-y-5">
                    @csrf
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-fondo" for="nombre">Nombre completo <span class="text-red-700">*</span></label>
                            <input id="nombre" name="nombre" value="{{ old('nombre') }}" required maxlength="120" autocomplete="name" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                            @error('nombre')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-fondo" for="empresa">Empresa <span class="text-red-700">*</span></label>
                            <input id="empresa" name="empresa" value="{{ old('empresa') }}" required maxlength="160" autocomplete="organization" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                            @error('empresa')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-fondo" for="correo">Correo corporativo <span class="text-red-700">*</span></label>
                            <input id="correo" name="correo" type="email" value="{{ old('correo') }}" required maxlength="160" autocomplete="email" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                            @error('correo')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-fondo" for="telefono">Teléfono <span class="text-fondo/55">(opcional)</span></label>
                            <input id="telefono" name="telefono" type="tel" value="{{ old('telefono') }}" maxlength="40" autocomplete="tel" class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                            @error('telefono')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-fondo" for="especialidad">Especialidad <span class="text-fondo/55">(opcional)</span></label>
                            <select id="especialidad" name="especialidad" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                                <option value="">Selecciona una especialidad</option>
                                @foreach (['Izaje y Manejo de Cargas', 'Ensayos No Destructivos', 'Oil & Gas', 'Técnicas de Mantenimiento'] as $especialidad)
                                    <option value="{{ $especialidad }}" @selected(old('especialidad') === $especialidad)>{{ $especialidad }}</option>
                                @endforeach
                            </select>
                            @error('especialidad')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-fondo" for="modalidad">Modalidad preferida <span class="text-fondo/55">(opcional)</span></label>
                            <select id="modalidad" name="modalidad" class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">
                                <option value="">Selecciona una modalidad</option>
                                @foreach (['ADHOC', 'INHOUSE', 'Semipresencial', 'Virtual'] as $modalidad)
                                    <option value="{{ $modalidad }}" @selected(old('modalidad') === $modalidad)>{{ $modalidad }}</option>
                                @endforeach
                            </select>
                            @error('modalidad')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-fondo" for="mensaje">Requerimientos de tu operación</label>
                        <textarea id="mensaje" name="mensaje" required maxlength="4000" rows="5" placeholder="Cantidad de participantes, fechas tentativas y necesidades específicas..." class="w-full rounded-lg border border-slate-300 px-4 py-3 focus:border-marca focus:outline-none focus:ring-2 focus:ring-marca/20">{{ old('mensaje') }}</textarea>
                        @error('mensaje')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>

                    <button class="fuente-ui inline-flex w-full items-center justify-center gap-2 rounded-lg bg-marca px-5 py-3.5 font-semibold text-white transition-colors hover:bg-marca-2 sm:w-auto" type="submit">
                        <i class="ti ti-send" aria-hidden="true"></i> Enviar solicitud
                    </button>
                </form>
            </section>
        </div>
    </section>
@endsection
