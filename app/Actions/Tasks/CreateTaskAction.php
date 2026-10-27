<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\DTOs\Tasks\TaskData;
use App\Models\Task;
use App\Models\User;

class CreateTaskAction
{
    /**
     * Create a new task for the given user.
     */
    public function execute(User $user, TaskData $data): Task
    {
        return Task::create([
            'user_id' => $user->id,
            'project_id' => $data->projectId,
            'title' => $data->title,
            'description' => $data->description,
            'priority' => $data->priority,
            'status' => $data->status,
            'due_date' => $data->dueDate,
            'estimated_pomodoros' => $data->estimatedPomodoros,
            'completed_pomodoros' => 0,
        ]);
    }
}
