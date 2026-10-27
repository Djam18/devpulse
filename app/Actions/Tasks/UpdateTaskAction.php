<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\DTOs\Tasks\TaskData;
use App\Models\Task;

class UpdateTaskAction
{
    /**
     * Update an existing task.
     */
    public function execute(Task $task, TaskData $data): Task
    {
        $task->update([
            'project_id' => $data->projectId,
            'title' => $data->title,
            'description' => $data->description,
            'priority' => $data->priority,
            'status' => $data->status,
            'due_date' => $data->dueDate,
            'estimated_pomodoros' => $data->estimatedPomodoros,
        ]);

        return $task->fresh(['project']);
    }
}
