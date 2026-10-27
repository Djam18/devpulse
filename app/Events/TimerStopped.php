<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\TimeEntry;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimerStopped
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public TimeEntry $timeEntry
    ) {}
}
