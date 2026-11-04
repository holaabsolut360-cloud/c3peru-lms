<?php

namespace App\Models;

use Database\Factories\PagoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    /** @use HasFactory<PagoFactory> */
    use HasFactory;

    protected $table = 'pagos';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'aprobado_en' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class);
    }

    public function aprobador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprobado_por');
    }
}
