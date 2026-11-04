<?php

namespace App\Models;

use Database\Factories\InsigniaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insignia extends Model
{
    /** @use HasFactory<InsigniaFactory> */
    use HasFactory;

    protected $table = 'insignias';

    protected $guarded = ['id'];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function otorgamientos(): HasMany
    {
        return $this->hasMany(InsigniaUsuario::class, 'insignia_id');
    }
}
