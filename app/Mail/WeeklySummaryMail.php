<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class WeeklySummaryMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @param  array<int, array{name: string, color: string, hours: float}>  $topProjects
     */
    public function __construct(
        public User $user,
        public float $totalHours,
        public int $pomodorosCount,
        public int $completedTasksCount,
        public array $topProjects = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📊 Votre récapitulatif hebdomadaire DevPulse ('.$this->totalHours.'h)',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.weekly-summary',
            with: [
                'user' => $this->user,
                'totalHours' => $this->totalHours,
                'pomodorosCount' => $this->pomodorosCount,
                'completedTasksCount' => $this->completedTasksCount,
                'topProjects' => $this->topProjects,
                'analyticsUrl' => url('/dashboard'),
            ],
        );
    }
}
