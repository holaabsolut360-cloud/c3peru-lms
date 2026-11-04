@extends('layouts.panel')
@section('titulo', 'Administración')

@section('contenido')
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="fuente-ui text-sm font-semibold uppercase tracking-[0.16em] text-marca-2">Panel de control</p>
            <h1 class="mt-1 text-3xl text-fondo">Resumen general</h1>
            <p class="mt-2 text-sm text-slate-600">Indicadores actualizados con la actividad de la plataforma.</p>
        </div>
        <a href="{{ route('admin.pagos') }}" class="inline-flex w-fit items-center gap-2 rounded-xl bg-marca px-4 py-2.5 text-sm font-semibold text-white transition hover:brightness-110">
            <i class="ti ti-receipt" aria-hidden="true"></i>
            Revisar pagos
        </a>
    </div>

    <section class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Indicadores principales">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Alumnos registrados</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-fondo">{{ number_format($metrics['alumnos']) }}</p>
                </div>
                <span class="grid size-11 place-items-center rounded-xl bg-sky-50 text-xl text-sky-700"><i class="ti ti-users" aria-hidden="true"></i></span>
            </div>
            <p class="mt-4 text-xs text-slate-500">Cuentas con rol de alumno</p>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Cursos publicados</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-fondo">{{ number_format($metrics['cursosPublicados']) }}</p>
                </div>
                <span class="grid size-11 place-items-center rounded-xl bg-violet-50 text-xl text-violet-700"><i class="ti ti-book-2" aria-hidden="true"></i></span>
            </div>
            <p class="mt-4 text-xs text-slate-500">Disponibles en la plataforma</p>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Ingresos del mes</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-fondo">S/ {{ number_format($metrics['ingresoMes'], 2) }}</p>
                </div>
                <span class="grid size-11 place-items-center rounded-xl bg-emerald-50 text-xl text-emerald-700"><i class="ti ti-currency-dollar" aria-hidden="true"></i></span>
            </div>
            <p class="mt-4 text-xs text-slate-500">Pagos aprobados este mes</p>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-sm font-medium text-slate-600">Certificados emitidos</p>
                    <p class="mt-3 text-3xl font-bold tracking-tight text-fondo">{{ number_format($metrics['certificados']) }}</p>
                </div>
                <span class="grid size-11 place-items-center rounded-xl bg-amber-50 text-xl text-amber-700"><i class="ti ti-certificate" aria-hidden="true"></i></span>
            </div>
            <p class="mt-4 text-xs text-slate-500">Total registrados</p>
        </article>
    </section>

    <section class="mt-6 grid gap-5 xl:grid-cols-5" aria-label="Análisis de actividad">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-3">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-fondo">Ingresos mensuales</h2>
                    <p class="mt-1 text-sm text-slate-500">Pagos aprobados durante los últimos seis meses</p>
                </div>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-800">Soles (S/)</span>
            </div>
            <div
                class="mt-4 min-h-64"
                data-admin-chart="ingresos"
                data-labels="{{ $ingresosMensuales->pluck('etiqueta')->values()->toJson() }}"
                data-values="{{ $ingresosMensuales->pluck('total')->values()->toJson() }}"
                role="img"
                aria-label="Gráfico de ingresos mensuales"
            ></div>
        </article>

        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div>
                <h2 class="text-lg font-bold text-fondo">Cursos más matriculados</h2>
                <p class="mt-1 text-sm text-slate-500">Inscripciones acumuladas por curso</p>
            </div>
            <div
                class="mt-4 min-h-64"
                data-admin-chart="cursos"
                data-labels="{{ $cursosPopulares->pluck('titulo')->values()->toJson() }}"
                data-values="{{ $cursosPopulares->pluck('matriculas_count')->values()->toJson() }}"
                role="img"
                aria-label="Gráfico de cursos más matriculados"
            ></div>
        </article>
    </section>

    <section class="mt-5 grid gap-5 xl:grid-cols-5" aria-label="Inscripciones y pagos">
        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm xl:col-span-2">
            <div>
                <h2 class="text-lg font-bold text-fondo">Inscripciones por semana</h2>
                <p class="mt-1 text-sm text-slate-500">Últimas cuatro semanas</p>
            </div>
            <div
                class="mt-4 min-h-64"
                data-admin-chart="inscripciones"
                data-labels="{{ $inscripcionesSemanales->pluck('etiqueta')->values()->toJson() }}"
                data-values="{{ $inscripcionesSemanales->pluck('total')->values()->toJson() }}"
                role="img"
                aria-label="Gráfico de inscripciones semanales"
            ></div>
        </article>

        <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-3">
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-4">
                <div>
                    <h2 class="text-lg font-bold text-fondo">Pagos pendientes</h2>
                    <p class="mt-1 text-sm text-slate-500">Comprobantes que esperan revisión</p>
                </div>
                <a href="{{ route('admin.pagos') }}" class="text-sm font-semibold text-marca hover:underline">Ver todos</a>
            </div>

            @if ($pagosPendientes->isEmpty())
                <p class="border-t border-slate-100 px-5 py-8 text-center text-sm text-slate-500">No hay pagos pendientes de revisión.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[34rem] text-left text-sm">
                        <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                            <tr>
                                <th scope="col" class="px-5 py-3 font-semibold">Alumno</th>
                                <th scope="col" class="px-5 py-3 font-semibold">Curso</th>
                                <th scope="col" class="px-5 py-3 text-right font-semibold">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pagosPendientes as $pago)
                                <tr>
                                    <td class="px-5 py-3.5 font-medium text-fondo">{{ $pago->usuario?->name ?? 'Alumno' }}</td>
                                    <td class="max-w-56 truncate px-5 py-3.5 text-slate-600">{{ $pago->curso?->titulo ?? 'Curso' }}</td>
                                    <td class="whitespace-nowrap px-5 py-3.5 text-right font-semibold text-fondo">{{ $pago->moneda }} {{ number_format((float) $pago->monto, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>
    </section>
@endsection
