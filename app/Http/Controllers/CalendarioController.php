<?php

namespace App\Http\Controllers;

use App\Models\ClaseEnVivo;
use App\Models\Matricula;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CalendarioController extends Controller
{
    public function descargar(ClaseEnVivo $clase): Response
    {
        $usuario = auth()->user();
        $esAlumnoMatriculado = $usuario->role === 'alumno'
            && Matricula::query()
                ->where('user_id', $usuario->id)
                ->where('curso_id', $clase->curso_id)
                ->where('estado', 'activa')
                ->exists();
        $esDocenteAsignado = $usuario->role === 'docente'
            && (int) $clase->docente_id === (int) $usuario->id;

        abort_unless($esAlumnoMatriculado || $esDocenteAsignado, 403);
        abort_unless($clase->estado !== 'cancelada', 404);

        $clase->loadMissing(['curso', 'leccion']);
        $descripcion = $clase->curso->titulo;
        if ($clase->leccion) {
            $descripcion .= ' - '.$clase->leccion->titulo;
        }
        if (! $clase->esEnlaceSimulado() && $clase->teams_join_url) {
            $descripcion .= "\nEnlace de reunión: {$clase->teams_join_url}";
        }

        $lineas = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//C3 Peru//Aula Virtual//ES',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:clase-'.$clase->getKey().'@c3peru.com',
            'DTSTAMP:'.now()->utc()->format('Ymd\THis\Z'),
            'DTSTART:'.$clase->fecha_inicio->copy()->utc()->format('Ymd\THis\Z'),
            'DTEND:'.$clase->fecha_fin->copy()->utc()->format('Ymd\THis\Z'),
            'SUMMARY:'.$this->escapar((string) $clase->titulo),
            'DESCRIPTION:'.$this->escapar($descripcion),
            'STATUS:CONFIRMED',
            'TRANSP:OPAQUE',
            'BEGIN:VALARM',
            'TRIGGER:-PT15M',
            'ACTION:DISPLAY',
            'DESCRIPTION:La clase en vivo comienza en 15 minutos',
            'END:VALARM',
            'END:VEVENT',
            'END:VCALENDAR',
        ];

        $contenido = implode("\r\n", array_map($this->plegar(...), $lineas))."\r\n";
        $nombreArchivo = Str::slug($clase->titulo) ?: 'clase-'.$clase->getKey();

        return response($contenido, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="'.$nombreArchivo.'.ics"',
            'Cache-Control' => 'private, no-store',
        ]);
    }

    private function escapar(string $valor): string
    {
        return str_replace(
            ['\\', ';', ',', "\r\n", "\r", "\n"],
            ['\\\\', '\\;', '\\,', '\\n', '\\n', '\\n'],
            $valor
        );
    }

    private function plegar(string $linea): string
    {
        $caracteres = preg_split('//u', $linea, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $partes = [];
        $parte = '';
        $bytes = 0;

        foreach ($caracteres as $caracter) {
            $longitud = strlen($caracter);
            if ($bytes + $longitud > 75) {
                $partes[] = $parte;
                $parte = ' ';
                $bytes = 1;
            }
            $parte .= $caracter;
            $bytes += $longitud;
        }

        $partes[] = $parte;

        return implode("\r\n", $partes);
    }
}
