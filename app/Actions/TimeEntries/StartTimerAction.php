<?php

declare(strict_types=1);

namespace App\Actions\TimeEntries;

use App\DTOs\TimeEntries\StartTimerData;
use App\Events\TimerStarted;
use App\Models\TimeEntry;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StartTimerAction
{
    public function __construct(
        private StopTimerAction $stopTimerAction
    ) {}

    /**
     * Start a new timer for the user, stopping any active timer first.
     */
    public function execute(User $user, StartTimerData $data): TimeEntry
    {
        return DB::transaction(function () use ($user, $data) {
            // Find and stop all ongoing running timers for this user
            $activeTimers = $user->timeEntries()
                ->whereNull('ended_at')
                ->lockForUpdate()
                ->get();

            foreach ($activeTimers as $activeTimer) {
                $this->stopTimerAction->execute($activeTimer);
            }

            $timeEntry = $user->timeEntries()->create([
                'project_id' => $data->projectId,
                'task_id' => $data->taskId,
                'description' => $data->description,
                'is_pomodoro' => $data->isPomodoro,
                'started_at' => now(),
            ]);

            TimerStarted::dispatch($timeEntry);

            return $timeEntry->load(['project', 'task']);
        });
    }
}
