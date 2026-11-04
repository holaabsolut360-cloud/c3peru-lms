<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActividadAlumno extends Model
{
    public const string TIPO_CURSO_VISITADO = 'curso_visitado';

    public const string TIPO_VIDEO_VISTO = 'video_visto';

    public const string TIPO_LECCION_COMPLETADA = 'leccion_completada';

    public const string TIPO_EXAMEN_APROBADO = 'examen_aprobado';

    public const string TIPO_EXAMEN_REPROBADO = 'examen_reprobado';

    public const string TIPO_TAREA_ENVIADA = 'tarea_enviada';

    protected $table = 'actividades_alumno';

    protected $fillable = [
        'user_id',
        'curso_id',
        'leccion_id',
        'tipo',
        'duracion_segundos',
        'porcentaje_completado',
    ];

    protected function casts(): array
    {
        return [
            'duracion_segundos' => 'integer',
            'porcentaje_completado' => 'integer',
        ];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function leccion(): BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }
}
