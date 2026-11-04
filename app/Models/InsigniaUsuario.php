<?php

namespace App\Models;

use Database\Factories\InsigniaUsuarioFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsigniaUsuario extends Model
{
    /** @use HasFactory<InsigniaUsuarioFactory> */
    use HasFactory;

    protected $table = 'insignias_usuario';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'otorgada_en' => 'datetime',
        ];
    }

    public function insignia(): BelongsTo
    {
        return $this->belongsTo(Insignia::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
