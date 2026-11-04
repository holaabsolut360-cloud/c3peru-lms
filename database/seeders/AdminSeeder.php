<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use InvalidArgumentException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('admin.email');
        $password = config('admin.password');

        if (! is_string($email) || ! is_string($password) || $email === '' || $password === '') {
            $this->command?->warn('ADMIN_EMAIL y ADMIN_PASSWORD no están definidos. No se creó el administrador.');

            return;
        }

        if (strlen($password) < 12) {
            throw new InvalidArgumentException('ADMIN_PASSWORD debe tener al menos 12 caracteres.');
        }

        User::query()->updateOrCreate(
            ['email' => mb_strtolower(trim($email))],
            [
                'name' => 'Administración C3 Perú',
                'password' => $password,
                'role' => 'admin',
                'documento_tipo' => null,
                'documento_numero' => null,
                'email_verified_at' => now(),
            ],
        );
    }
}
