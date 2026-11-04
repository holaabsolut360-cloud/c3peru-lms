<?php

namespace App\Notifications;

use App\Models\Curso;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecordatorioCurso extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Curso $curso, public float $progreso) {}

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
            ->subject('¿Necesitas apoyo en tu curso?')
            ->greeting('Hola '.$notifiable->name)
            ->line('Tu docente quiere saber cómo vas en '.$this->curso->titulo.'.')
            ->line('Tu avance registrado es de '.number_format($this->progreso, 0).'%.')
            ->action('Continuar el curso', route('aula.curso', $this->curso));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return ['curso_id' => $this->curso->id, 'progreso' => $this->progreso];
    }
}
