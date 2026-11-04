<?php

namespace App\Models;

use Database\Factories\LeccionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Leccion extends Model
{
    /** @use HasFactory<LeccionFactory> */
    use HasFactory;

    protected $table = 'lecciones';

    protected $guarded = ['id'];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function clasesEnVivo(): HasMany
    {
        return $this->hasMany(ClaseEnVivo::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function materiales(): HasMany
    {
        return $this->hasMany(LeccionMaterial::class);
    }
}
