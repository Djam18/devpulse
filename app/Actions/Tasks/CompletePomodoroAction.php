<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\Task;

final readonly class CompletePomodoroAction
{
    /**
     * Increment the completed pomodoros count for a task.
     */
    public function execute(Task $task): Task
    {
        $task->increment('completed_pomodoros');

        return $task->fresh(['project']);
    }
}
