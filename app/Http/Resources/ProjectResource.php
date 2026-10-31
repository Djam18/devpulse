<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Project
 */
class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'name' => $this->name,
            'color' => $this->color,
            'estimated_hours' => (float) $this->estimated_hours,
            'logged_hours' => round(((float) ($this->total_logged_seconds ?? 0)) / 3600, 1),
            'status' => $this->status->value,
            'client' => $this->whenLoaded('client', function () {
                return $this->client ? [
                    'id' => $this->client->id,
                    'name' => $this->client->name,
                    'color' => $this->client->color,
                    'hourly_rate' => $this->client->hourly_rate !== null ? (float) $this->client->hourly_rate : null,
                ] : null;
            }),
            'tasks_count' => $this->whenCounted('tasks'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
