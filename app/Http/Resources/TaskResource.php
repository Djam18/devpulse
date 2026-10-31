<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Task
 */
class TaskResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority->value,
            'status' => $this->status->value,
            'order_column' => $this->order_column ?? 0,
            'due_date' => $this->due_date?->format('Y-m-d'),
            'estimated_pomodoros' => $this->estimated_pomodoros,
            'completed_pomodoros' => $this->completed_pomodoros,
            'project_id' => $this->project_id,
            'project' => $this->whenLoaded('project', function () {
                return $this->project ? [
                    'id' => $this->project->id,
                    'name' => $this->project->name,
                    'color' => $this->project->color,
                ] : null;
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
