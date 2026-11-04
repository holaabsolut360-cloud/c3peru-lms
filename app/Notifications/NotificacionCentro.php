<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NotificacionCentro extends Notification
{
    public function __construct(
        public string $titulo,
        public string $mensaje,
        public string $url,
        public string $categoria,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string, string>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => $this->titulo,
            'mensaje' => $this->mensaje,
            'url' => $this->url,
            'categoria' => $this->categoria,
        ];
    }
}
