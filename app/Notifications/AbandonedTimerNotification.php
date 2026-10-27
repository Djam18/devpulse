<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\TimeEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class AbandonedTimerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TimeEntry $timeEntry
    ) {}

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
        $startedAt = $this->timeEntry->started_at;
        $diffMinutes = max(1, (int) $startedAt->diffInMinutes(now()));
        $hours = intdiv($diffMinutes, 60);
        $minutes = $diffMinutes % 60;

        return (new MailMessage)
            ->subject('⏱️ Votre chronomètre DevPulse tourne toujours ('.$hours.'h)')
            ->view('emails.abandoned-timer', [
                'user' => $notifiable,
                'taskTitle' => $this->timeEntry->task?->title ?? $this->timeEntry->description ?? 'Session de travail',
                'hours' => $hours,
                'minutes' => $minutes,
                'startedAt' => $startedAt,
                'timerUrl' => url('/tasks'),
            ]);
    }
}
