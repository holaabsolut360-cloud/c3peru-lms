<?php

namespace App\Services;

use App\Models\ClaseEnVivo;
use Illuminate\Support\Str;

class MicrosoftTeamsService
{
    /**
     * @return array{meeting_id: string, join_url: string}
     */
    public function crearReunion(ClaseEnVivo $clase): array
    {
        return [
            'meeting_id' => 'mock-meeting-'.Str::random(10),
            'join_url' => 'https://teams.microsoft.com/l/meetup-join/mock-'.Str::random(8),
        ];
    }
}
