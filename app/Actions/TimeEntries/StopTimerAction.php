<?php

declare(strict_types=1);

namespace App\Actions\TimeEntries;

use App\Events\TimerStopped;
use App\Models\TimeEntry;
use Carbon\CarbonInterface;

class StopTimerAction
{
    /**
     * Stop a running time entry and calculate its duration.
     */
    public function execute(TimeEntry $timeEntry, ?CarbonInterface $endedAt = null): TimeEntry
    {
        $endTime = $endedAt ?? now();
        $duration = abs((int) $endTime->diffInSeconds($timeEntry->started_at));

        $timeEntry->update([
            'ended_at' => $endTime,
            'duration_seconds' => $duration,
        ]);

        TimerStopped::dispatch($timeEntry);

        return $timeEntry;
    }
}
