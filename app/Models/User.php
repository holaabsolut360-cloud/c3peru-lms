<?php

namespace App\Models;

use App\Notifications\RestablecerContrasena;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable(['name', 'email', 'password', 'role', 'documento_tipo', 'documento_numero', 'telefono'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, LogsActivity, Notifiable;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role'])
            ->logOnlyDirty()
            ->dontLogEmptyChanges()
            ->setDescriptionForEvent(fn (string $eventName): string => match ($eventName) {
                'created' => 'Usuario creado',
                'updated' => 'Usuario actualizado',
                'deleted' => 'Usuario eliminado',
                default => 'Actividad de usuario',
            });
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function esAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function esDocente(): bool
    {
        return $this->role === 'docente';
    }

    public function rutaInicio(): string
    {
        return match ($this->role) {
            'admin' => route('admin.inicio'),
            'docente' => route('docente.inicio'),
            default => route('aula.inicio'),
        };
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new RestablecerContrasena($token));
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    public function certificados(): HasMany
    {
        return $this->hasMany(Certificado::class);
    }

    public function insignias(): HasMany
    {
        return $this->hasMany(InsigniaUsuario::class, 'user_id');
    }

    public function examenIntentos(): HasMany
    {
        return $this->hasMany(ExamenIntento::class);
    }

    public function tareasDocente(): HasMany
    {
        return $this->hasMany(Tarea::class, 'docente_id');
    }

    public function entregasTarea(): HasMany
    {
        return $this->hasMany(TareaEntrega::class, 'user_id');
    }
}
