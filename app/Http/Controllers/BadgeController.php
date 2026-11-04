<?php

namespace App\Http\Controllers;

use App\Models\Insignia;
use App\Models\InsigniaUsuario;
use App\Services\OpenBadgesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BadgeController extends Controller
{
    public function verificacion(string $assertionId): JsonResponse
    {
        abort_unless(Str::isUuid($assertionId), 404);

        $disco = Storage::disk('public');
        $ruta = 'badges/'.$assertionId.'.json';
        abort_unless($disco->exists($ruta), 404, 'Insignia no encontrada.');
        $datos = $disco->get($ruta);

        return response()->json(json_decode($datos, true, flags: JSON_THROW_ON_ERROR))
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function emisor(OpenBadgesService $openBadges): JsonResponse
    {
        return response()->json($openBadges->metadataEmisor())
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function clase(Insignia $insignia, OpenBadgesService $openBadges): JsonResponse
    {
        return response()->json($openBadges->metadataInsignia($insignia))
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function compartirLinkedin(InsigniaUsuario $insigniaUsuario): RedirectResponse
    {
        abort_unless((int) $insigniaUsuario->user_id === (int) auth()->id(), 403);
        $insigniaUsuario->load('insignia');
        $url = 'https://www.linkedin.com/profile/add?'.http_build_query([
            'startTask' => 'CERTIFICATION_NAME',
            'name' => $insigniaUsuario->insignia->nombre,
            'organizationName' => 'C3 Perú',
            'issueYear' => $insigniaUsuario->otorgada_en->year,
            'issueMonth' => $insigniaUsuario->otorgada_en->month,
            'certUrl' => $insigniaUsuario->assertion_url,
        ], '', '&', PHP_QUERY_RFC3986);

        return redirect()->away($url);
    }
}
