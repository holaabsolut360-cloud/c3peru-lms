<?php

namespace App\Notifications;

use App\Models\AnuncioCurso;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnuncioCursoPublicado extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public AnuncioCurso $anuncio) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nuevo anuncio: '.$this->anuncio->titulo)
            ->greeting('Hola '.$notifiable->name)
            ->line('Se publicó un nuevo anuncio para el curso '.$this->anuncio->curso->titulo.'.')
            ->line($this->anuncio->contenido)
            ->action('Ver curso', route('aula.curso', $this->anuncio->curso));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return ['anuncio_id' => $this->anuncio->id];
    }
}
