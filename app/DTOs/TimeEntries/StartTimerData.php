<?php

declare(strict_types=1);

namespace App\DTOs\TimeEntries;

final readonly class StartTimerData
{
    public function __construct(
        public ?int $projectId = null,
        public ?int $taskId = null,
        public ?string $description = null,
        public bool $isPomodoro = false,
    ) {}
}
