<?php

declare(strict_types=1);

namespace App\Actions\TimeEntries;

use App\DTOs\TimeEntries\LogManualTimeData;
use App\Models\TimeEntry;
use App\Models\User;

class LogManualTimeAction
{
    /**
     * Log a retroactive manual time entry.
     */
    public function execute(User $user, LogManualTimeData $data): TimeEntry
    {
        $duration = abs((int) $data->endedAt->diffInSeconds($data->startedAt));

        /** @var TimeEntry $timeEntry */
        $timeEntry = $user->timeEntries()->create([
            'project_id' => $data->projectId,
            'task_id' => $data->taskId,
            'description' => $data->description,
            'is_pomodoro' => $data->isPomodoro,
            'started_at' => $data->startedAt,
            'ended_at' => $data->endedAt,
            'duration_seconds' => $duration,
        ]);

        return $timeEntry->load(['project', 'task']);
    }
}
