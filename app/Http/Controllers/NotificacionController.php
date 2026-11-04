<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificacionController extends Controller
{
    public function index(): View
    {
        $usuario = auth()->user();

        return view('notificaciones.index', [
            'notificaciones' => $usuario->notifications()->latest()->paginate(25),
            'noLeidas' => $usuario->unreadNotifications()->count(),
        ]);
    }

    public function marcarLeida(string $notificationId): RedirectResponse
    {
        $notificacion = auth()->user()->notifications()->findOrFail($notificationId);
        $notificacion->markAsRead();

        return back()->with('estado', 'Notificación marcada como leída.');
    }

    public function abrir(string $notificationId): RedirectResponse
    {
        $notificacion = auth()->user()->notifications()->findOrFail($notificationId);
        $notificacion->markAsRead();
        $destino = $notificacion->data['url'] ?? null;

        if (is_string($destino) && str_starts_with($destino, '/') && ! str_starts_with($destino, '//') && ! str_contains($destino, '\\')) {
            return redirect($destino);
        }

        return to_route('notificaciones.index');
    }

    public function marcarTodas(): RedirectResponse
    {
        auth()->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('estado', 'Todas las notificaciones quedaron marcadas como leídas.');
    }
}
