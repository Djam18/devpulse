<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\TimerStopped;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

final class RecalculateTaskTotalTime implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(TimerStopped $event): void
    {
        $timeEntry = $event->timeEntry;

        if ($timeEntry->task_id === null) {
            return;
        }

        // Sum durations for this task
        $totalSeconds = (int) $timeEntry->task
            ?->timeEntries()
            ->whereNotNull('ended_at')
            ->sum('duration_seconds');

        Log::info('Task duration recalculated after timer stopped', [
            'task_id' => $timeEntry->task_id,
            'total_seconds' => $totalSeconds,
            'session_duration' => $timeEntry->duration_seconds,
        ]);
    }
}
