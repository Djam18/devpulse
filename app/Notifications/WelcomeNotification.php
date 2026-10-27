<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
            ->subject('⚡ Bienvenue sur DevPulse !')
            ->greeting('Bonjour '.$notifiable->name.',')
            ->line('Votre compte DevPulse a été configuré avec succès.')
            ->line('Vous pouvez dès maintenant suivre votre temps de code, gérer votre Kanban de sprint et booster votre concentration avec Pomodoro.')
            ->action('Accéder à mon tableau de bord', url('/dashboard'))
            ->line('Bonne session de code haute vélocité !');
    }
}
