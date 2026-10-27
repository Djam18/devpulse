<?php

declare(strict_types=1);

namespace App\DTOs\Projects;

use App\Enums\ProjectStatus;

final readonly class ProjectData
{
    public function __construct(
        public string $name,
        public string $color,
        public ?float $estimatedHours = null,
        public ?int $clientId = null,
        public ProjectStatus $status = ProjectStatus::Active,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            color: (string) ($data['color'] ?? '#7D2D68'),
            estimatedHours: isset($data['estimated_hours']) ? (float) $data['estimated_hours'] : null,
            clientId: isset($data['client_id']) ? (int) $data['client_id'] : null,
            status: isset($data['status'])
                ? ($data['status'] instanceof ProjectStatus ? $data['status'] : ProjectStatus::from((string) $data['status']))
                : ProjectStatus::Active,
        );
    }
}
