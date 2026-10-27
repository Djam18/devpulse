<?php

declare(strict_types=1);

namespace App\DTOs\Tasks;

use App\Enums\TaskStatus;

readonly class UpdateTaskStatusData
{
    public function __construct(
        public TaskStatus $status,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            status: isset($data['status']) && $data['status'] instanceof TaskStatus
                ? $data['status']
                : TaskStatus::from((string) $data['status']),
        );
    }
}
