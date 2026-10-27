<?php

declare(strict_types=1);

namespace App\Http\Requests\Tasks;

use App\DTOs\Tasks\TaskData;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var Task|null $task */
        $task = $this->route('task');

        return $task !== null && $this->user()?->can('update', $task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'project_id' => [
                'nullable',
                'integer',
                Rule::exists('projects', 'id')->where('user_id', $this->user()?->id),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'priority' => ['required', Rule::enum(TaskPriority::class)],
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'due_date' => ['nullable', 'date'],
            'estimated_pomodoros' => ['required', 'integer', 'min:1', 'max:20'],
        ];
    }

    /**
     * Convert request to DTO.
     */
    public function toData(): TaskData
    {
        return TaskData::fromArray($this->validated());
    }
}
