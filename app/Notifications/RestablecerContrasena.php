<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RestablecerContrasena extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        return (new MailMessage)
            ->subject('Restablece tu contraseña')
            ->greeting('Hola '.$notifiable->name)
            ->line('Recibimos una solicitud para restablecer la contraseña de tu cuenta de alumno o docente.')
            ->action('Cambiar contraseña', $url)
            ->line('Si no fuiste tú, ignora este mensaje. El enlace caduca en 60 minutos.')
            ->salutation('Equipo '.config('marca.nombre_comercial'));
    }
}
