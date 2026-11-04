<?php

namespace App\Services;

use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\URL;

class VideoProtectionService
{
    public function generarUrlFirmada(Video $video, User $usuario, int $minutosExpiracion = 15): string
    {
        abort_unless($video->protegido, 404);

        return URL::temporarySignedRoute(
            'aula.videos.stream',
            now()->addMinutes($minutosExpiracion),
            ['video' => $video->getKey(), 'user_id' => $usuario->getKey()],
        );
    }
}
