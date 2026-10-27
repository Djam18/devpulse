<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Mail\WeeklySummaryMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
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
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): Mailable
    {
        return (new WeeklySummaryMail(
            user: $notifiable,
            totalHours: $this->totalHours,
            pomodorosCount: $this->pomodorosCount,
            completedTasksCount: $this->completedTasksCount,
            topProjects: $this->topProjects,
        ))->to($notifiable->email);
    }
}
