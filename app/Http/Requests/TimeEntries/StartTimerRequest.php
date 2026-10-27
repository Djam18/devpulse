<?php

declare(strict_types=1);

namespace App\Http\Requests\TimeEntries;

use App\DTOs\TimeEntries\StartTimerData;
use Illuminate\Foundation\Http\FormRequest;

class StartTimerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'task_id' => ['nullable', 'integer', 'exists:tasks,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_pomodoro' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Map validated data to an immutable DTO.
     */
    public function toDTO(): StartTimerData
    {
        return new StartTimerData(
            projectId: $this->filled('project_id') ? (int) $this->input('project_id') : null,
            taskId: $this->filled('task_id') ? (int) $this->input('task_id') : null,
            description: $this->filled('description') ? (string) $this->input('description') : null,
            isPomodoro: (bool) $this->boolean('is_pomodoro'),
        );
    }
}
