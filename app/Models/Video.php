<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    /** @use HasFactory<\Database\Factories\VideoFactory> */
    use HasFactory;

    protected $table = 'videos';

    protected $guarded = ['id'];

    protected $hidden = ['archivo_path'];

    protected function casts(): array
    {
        return [
            'duracion_segundos' => 'integer',
            'protegido' => 'boolean',
        ];
    }

    public function leccion(): BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }
}
