<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Mail\LongRunningTimerMail;
use App\Models\TimeEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

final class LongRunningTimerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TimeEntry $timeEntry
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
        return (new LongRunningTimerMail($notifiable, $this->timeEntry))->to($notifiable->email);
    }
}
