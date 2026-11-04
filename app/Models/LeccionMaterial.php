<?php

namespace App\Models;

use Database\Factories\LeccionMaterialFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeccionMaterial extends Model
{
    /** @use HasFactory<LeccionMaterialFactory> */
    use HasFactory;

    protected $table = 'leccion_materiales';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'tamano_bytes' => 'integer',
        ];
    }

    public function leccion(): BelongsTo
    {
        return $this->belongsTo(Leccion::class);
    }
}
