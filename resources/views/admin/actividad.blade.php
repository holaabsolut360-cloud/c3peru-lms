@extends('layouts.panel')
@section('titulo', 'Registro de auditoría')

@section('contenido')
    <h1 class="text-3xl text-marca">Registro de auditoría</h1>
    <p class="mt-2 text-sm">Cambios recientes realizados en usuarios y cursos.</p>

    <div class="mt-6 space-y-3">
        @forelse ($activities as $activity)
            <article class="rounded-2xl border p-4">
                <div class="flex flex-wrap items-start justify-between gap-2">
                    <div>
                        <p class="font-semibold">{{ $activity->description }}</p>
                        <p class="mt-1 text-sm">
                            {{ $activity->causer?->name ?? 'Sistema' }}
                            <span aria-hidden="true">·</span>
                            @if ($activity->subject instanceof \App\Models\Curso)
                                Curso: {{ $activity->subject->titulo }}
                            @elseif ($activity->subject instanceof \App\Models\User)
                                Usuario: {{ $activity->subject->name }}
                            @elseif ($activity->subject)
                                {{ class_basename($activity->subject) }} #{{ $activity->subject->getKey() }}
                            @else
                                Registro eliminado
                            @endif
                        </p>
                    </div>
                    <time class="text-sm text-marca" datetime="{{ $activity->created_at?->toIso8601String() }}">
                        {{ $activity->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                    </time>
                </div>

                @if ($activity->attribute_changes?->isNotEmpty())
                    <details class="mt-3 text-sm">
                        <summary class="cursor-pointer font-medium">Ver campos modificados</summary>
                        <pre class="mt-2 overflow-x-auto rounded-xl bg-gray-50 p-3 text-xs">{{ json_encode($activity->attribute_changes->toArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                    </details>
                @endif
            </article>
        @empty
            <p class="rounded-2xl border p-4 text-sm">Todavía no hay actividades registradas.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $activities->links() }}</div>
@endsection
