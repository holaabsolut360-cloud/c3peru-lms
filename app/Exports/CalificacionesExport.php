<?php

namespace App\Exports;

use App\Models\ExamenIntento;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CalificacionesExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private readonly int $cursoId) {}

    public function query(): Builder
    {
        return ExamenIntento::query()
            ->with(['usuario', 'examen'])
            ->whereNotNull('finalizado_en')
            ->whereHas('examen', fn (Builder $consulta) => $consulta->where('curso_id', $this->cursoId))
            ->orderBy('id');
    }

    public function headings(): array
    {
        return [
            'Alumno',
            'Documento',
            'Correo',
            'Examen',
            'Puntaje',
            'Nota / 20',
            'Resultado',
            'Inicio',
            'Finalización',
        ];
    }

    public function map(mixed $intento): array
    {
        return [
            $intento->usuario->name,
            $intento->usuario->documento_numero,
            $intento->usuario->email,
            $intento->examen->titulo,
            $intento->puntaje_total,
            number_format((float) $intento->nota_final, 2, '.', ''),
            $intento->aprobado ? 'Aprobado' : 'No aprobado',
            $intento->iniciado_en->timezone(config('app.timezone'))->format('d/m/Y H:i'),
            $intento->finalizado_en->timezone(config('app.timezone'))->format('d/m/Y H:i'),
        ];
    }
}
