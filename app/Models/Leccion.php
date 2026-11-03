<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leccion extends Model
{
    protected $table = 'lecciones';

    protected $guarded = ['id'];

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }
}
