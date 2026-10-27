<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\TimeEntry;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin TimeEntry
 */
class TimeEntryResource extends JsonResource
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
            'description' => $this->description,
            'started_at' => $this->started_at->toIso8601String(),
            'ended_at' => $this->ended_at?->toIso8601String(),
            'duration_seconds' => $this->duration_seconds ?? $this->currentDuration(),
            'is_running' => $this->isRunning(),
            'is_pomodoro' => $this->is_pomodoro,
            'project' => $this->whenLoaded('project', function () {
                return $this->project ? [
                    'id' => $this->project->id,
                    'name' => $this->project->name,
                    'color' => $this->project->color,
                ] : null;
            }),
            'task' => $this->whenLoaded('task', function () {
                return $this->task ? [
                    'id' => $this->task->id,
                    'title' => $this->task->title,
                    'status' => $this->task->status->value,
                ] : null;
            }),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
