<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class LongRunningTimerMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public TimeEntry $timeEntry
    ) {}

    public function envelope(): Envelope
    {
        $startedAt = $this->timeEntry->started_at;
        $diffMinutes = max(1, (int) $startedAt->diffInMinutes(now()));
        $hours = intdiv($diffMinutes, 60);

        return new Envelope(
            subject: '⏱️ Votre chronomètre DevPulse tourne toujours ('.$hours.'h)',
        );
    }

    public function content(): Content
    {
        $startedAt = $this->timeEntry->started_at;
        $diffMinutes = max(1, (int) $startedAt->diffInMinutes(now()));
        $hours = intdiv($diffMinutes, 60);
        $minutes = $diffMinutes % 60;

        return new Content(
            view: 'emails.long-running-timer',
            with: [
                'user' => $this->user,
                'taskTitle' => $this->timeEntry->task?->title ?? $this->timeEntry->description ?? 'Session de travail',
                'hours' => $hours,
                'minutes' => $minutes,
                'startedAt' => $startedAt,
                'timerUrl' => url('/tasks'),
            ],
        );
    }
}
