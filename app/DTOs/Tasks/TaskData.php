<?php

declare(strict_types=1);

namespace App\DTOs\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;

readonly class TaskData
{
    public function __construct(
        public string $title,
        public ?int $projectId = null,
        public ?string $description = null,
        public TaskPriority $priority = TaskPriority::Medium,
        public TaskStatus $status = TaskStatus::Todo,
        public ?string $dueDate = null,
        public int $estimatedPomodoros = 1,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: (string) $data['title'],
            projectId: isset($data['project_id']) && $data['project_id'] !== null ? (int) $data['project_id'] : null,
            description: isset($data['description']) && $data['description'] !== null ? (string) $data['description'] : null,
            priority: isset($data['priority']) && $data['priority'] instanceof TaskPriority
                ? $data['priority']
                : TaskPriority::from((string) ($data['priority'] ?? 'medium')),
            status: isset($data['status']) && $data['status'] instanceof TaskStatus
                ? $data['status']
                : TaskStatus::from((string) ($data['status'] ?? 'todo')),
            dueDate: isset($data['due_date']) && $data['due_date'] !== null ? (string) $data['due_date'] : null,
            estimatedPomodoros: (int) ($data['estimated_pomodoros'] ?? 1),
        );
    }
}
