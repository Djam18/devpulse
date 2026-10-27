<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\Task;

class DeleteTaskAction
{
    /**
     * Delete a task.
     */
    public function execute(Task $task): bool
    {
        return (bool) $task->delete();
    }
}
