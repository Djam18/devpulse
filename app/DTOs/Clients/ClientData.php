<?php

declare(strict_types=1);

namespace App\DTOs\Clients;

final readonly class ClientData
{
    public function __construct(
        public string $name,
        public string $color = '#6B7280',
        public ?float $hourlyRate = null,
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: (string) $data['name'],
            color: (string) ($data['color'] ?? '#6B7280'),
            hourlyRate: isset($data['hourly_rate']) && $data['hourly_rate'] !== null && $data['hourly_rate'] !== ''
                ? (float) $data['hourly_rate']
                : null,
        );
    }
}
