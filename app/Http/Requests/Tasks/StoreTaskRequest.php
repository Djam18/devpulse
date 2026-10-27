<?php

declare(strict_types=1);

namespace App\Http\Requests\Tasks;

use App\DTOs\Tasks\TaskData;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'priority' => ['nullable', Rule::enum(TaskPriority::class)],
            'status' => ['nullable', Rule::enum(TaskStatus::class)],
            'due_date' => ['nullable', 'date'],
            'estimated_pomodoros' => ['nullable', 'integer', 'min:1', 'max:20'],
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
