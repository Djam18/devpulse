<?php

declare(strict_types=1);

namespace App\DTOs\TimeEntries;

use Carbon\CarbonInterface;

final readonly class LogManualTimeData
{
    public function __construct(
        public CarbonInterface $startedAt,
        public CarbonInterface $endedAt,
        public ?int $projectId = null,
        public ?int $taskId = null,
        public ?string $description = null,
        public bool $isPomodoro = false,
    ) {}
}
