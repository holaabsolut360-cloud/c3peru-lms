<?php

namespace App\Services;

use App\Models\ExamenIntento;
use App\Models\Insignia;
use App\Models\InsigniaUsuario;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class OpenBadgesService
{
    public function emitirInsigniasPorExamenesAprobados(int $userId): void
    {
        $intentosAprobados = ExamenIntento::query()
            ->with(['examen.curso', 'usuario'])
            ->where('user_id', $userId)
            ->where('aprobado', true)
            ->whereNotNull('finalizado_en')
            ->latest('finalizado_en')
            ->get()
            ->unique(fn (ExamenIntento $intento): ?int => $intento->examen?->curso_id);

        foreach ($intentosAprobados as $intento) {
            if (! $intento->examen || ! $intento->usuario || ! $intento->examen->curso) {
                continue;
            }

            $this->otorgarPorExamenAprobado($intento);
        }
    }

    public function otorgarPorExamenAprobado(ExamenIntento $intento): ?InsigniaUsuario
    {
        $intento->loadMissing(['examen.curso', 'usuario']);
        if (! $intento->aprobado || ! $intento->finalizado_en || ! $intento->examen || ! $intento->usuario || ! $intento->examen->curso) {
            return null;
        }

        $curso = $intento->examen->curso;
        $insignia = Insignia::query()->firstOrCreate(
            ['curso_id' => $curso->id],
            [
                'nombre' => 'Curso aprobado: '.$curso->titulo,
                'descripcion' => 'Insignia digital otorgada por aprobar una evaluación de '.$curso->titulo.'.',
                'imagen_url' => '/marca/logo.png',
                'criterio_logro' => 'Aprobar un examen del curso con la nota mínima configurada (14/20 por defecto).',
            ],
        );

        $otorgamientoExistente = InsigniaUsuario::query()
            ->where('insignia_id', $insignia->id)
            ->where('user_id', $intento->user_id)
            ->first();
        if ($otorgamientoExistente) {
            return $otorgamientoExistente;
        }

        $assertionId = (string) Str::uuid();
        $assertionUrl = route('badges.ver', $assertionId);
        $badgeUrl = route('badges.class', $insignia);
        $otorgadaEn = $intento->finalizado_en;
        $salt = (string) Str::random(32);
        $assertion = [
            '@context' => 'https://w3id.org/openbadges/v2',
            'id' => $assertionUrl,
            'type' => 'Assertion',
            'recipient' => [
                'type' => 'email',
                'identity' => 'sha256$'.hash('sha256', $intento->usuario->email.$salt),
                'hashed' => true,
                'salt' => $salt,
            ],
            'issuedOn' => $otorgadaEn->toIso8601String(),
            'badge' => $badgeUrl,
            'verification' => [
                'type' => 'HostedBadge',
            ],
        ];

        $rutaAssertion = 'badges/'.$assertionId.'.json';
        $json = json_encode($assertion, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
        if (! Storage::disk('public')->put($rutaAssertion, $json)) {
            throw new RuntimeException('No se pudo guardar la insignia digital.');
        }

        return InsigniaUsuario::query()->create([
            'insignia_id' => $insignia->id,
            'user_id' => $intento->user_id,
            'badge_url' => $badgeUrl,
            'assertion_url' => $assertionUrl,
            'otorgada_en' => $otorgadaEn,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function metadataInsignia(Insignia $insignia): array
    {
        $imagen = Str::startsWith($insignia->imagen_url, ['https://', 'http://'])
            ? $insignia->imagen_url
            : asset(ltrim($insignia->imagen_url, '/'));

        return [
            '@context' => 'https://w3id.org/openbadges/v2',
            'id' => route('badges.class', $insignia),
            'type' => 'BadgeClass',
            'name' => $insignia->nombre,
            'description' => $insignia->descripcion,
            'image' => $imagen,
            'criteria' => [
                'narrative' => $insignia->criterio_logro ?? 'Aprobar un examen del curso con la nota mínima configurada.',
            ],
            'issuer' => $this->metadataEmisor(),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function metadataEmisor(): array
    {
        return [
            '@context' => 'https://w3id.org/openbadges/v2',
            'id' => route('badges.issuer'),
            'type' => 'Issuer',
            'name' => 'C3 Perú - Capacitación Certificación Calidad',
            'url' => url('/'),
        ];
    }
}
