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
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Le titre de la tâche est obligatoire.',
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'project_id.exists' => 'Le projet sélectionné n’existe pas ou ne vous appartient pas.',
            'priority.enum' => 'La priorité sélectionnée est invalide.',
            'status.enum' => 'Le statut sélectionné est invalide.',
            'due_date.date' => 'La date d’échéance doit être une date valide.',
            'estimated_pomodoros.min' => 'L’estimation doit être d’au moins 1 pomodoro.',
            'estimated_pomodoros.max' => 'L’estimation ne peut pas dépasser 20 pomodoros.',
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
