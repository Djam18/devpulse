<?php

declare(strict_types=1);

namespace App\Http\Requests\Tasks;

use App\DTOs\Tasks\UpdateTaskStatusData;
use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskStatusRequest extends FormRequest
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
            'status' => ['required', Rule::enum(TaskStatus::class)],
        ];
    }

    /**
     * Convert request to DTO.
     */
    public function toData(): UpdateTaskStatusData
    {
        return UpdateTaskStatusData::fromArray($this->validated());
    }
}
