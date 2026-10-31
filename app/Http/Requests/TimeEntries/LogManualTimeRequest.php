<?php

declare(strict_types=1);

namespace App\Http\Requests\TimeEntries;

use App\DTOs\TimeEntries\LogManualTimeData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class LogManualTimeRequest extends FormRequest
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
            'started_at' => ['required', 'date'],
            'ended_at' => ['required', 'date', 'after_or_equal:started_at'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'task_id' => ['nullable', 'integer', 'exists:tasks,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_pomodoro' => ['nullable', 'boolean'],
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
            'started_at.required' => 'L’heure de début est obligatoire.',
            'started_at.date' => 'L’heure de début doit être une date valide.',
            'ended_at.required' => 'L’heure de fin est obligatoire.',
            'ended_at.date' => 'L’heure de fin doit être une date valide.',
            'ended_at.after_or_equal' => 'L’heure de fin doit être postérieure ou égale à l’heure de début.',
            'project_id.exists' => 'Le projet sélectionné n’existe pas ou ne vous appartient pas.',
            'task_id.exists' => 'La tâche sélectionnée n’existe pas ou ne vous appartient pas.',
            'description.max' => 'La description ne peut pas dépasser 255 caractères.',
        ];
    }

    /**
     * Map validated data to an immutable DTO.
     */
    public function toDTO(): LogManualTimeData
    {
        return new LogManualTimeData(
            startedAt: Carbon::parse((string) $this->input('started_at')),
            endedAt: Carbon::parse((string) $this->input('ended_at')),
            projectId: $this->filled('project_id') ? (int) $this->input('project_id') : null,
            taskId: $this->filled('task_id') ? (int) $this->input('task_id') : null,
            description: $this->filled('description') ? (string) $this->input('description') : null,
            isPomodoro: (bool) $this->boolean('is_pomodoro'),
        );
    }
}
