<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

final class WeeklySummaryNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<int, array{name: string, color: string, hours: float}>  $topProjects
     */
    public function __construct(
        public float $totalHours,
        public int $pomodorosCount,
        public int $completedTasksCount,
        public array $topProjects = []
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
        return (new MailMessage)
            ->subject('📊 Votre récapitulatif hebdomadaire DevPulse ('.$this->totalHours.'h)')
            ->view('emails.weekly-summary', [
                'user' => $notifiable,
                'totalHours' => $this->totalHours,
                'pomodorosCount' => $this->pomodorosCount,
                'completedTasksCount' => $this->completedTasksCount,
                'topProjects' => $this->topProjects,
                'analyticsUrl' => url('/dashboard'),
            ]);
    }
}
