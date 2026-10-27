<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\DTOs\Tasks\UpdateTaskStatusData;
use App\Models\Task;

class UpdateTaskStatusAction
{
    /**
     * Transition a task to a new status.
     */
    public function execute(Task $task, UpdateTaskStatusData $data): Task
    {
        $task->update([
            'status' => $data->status,
        ]);

        return $task->fresh(['project']);
    }
}
