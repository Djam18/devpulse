<?php

declare(strict_types=1);

namespace App\Http\Requests\Projects;

use App\DTOs\Projects\ProjectData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'regex:/^#([a-f0-9]{6}|[a-f0-9]{3})$/i'],
            'client_id' => [
                'nullable',
                'integer',
                Rule::exists('clients', 'id')->where('user_id', $this->user()?->id),
            ],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
            'status' => ['nullable', 'string', 'in:active,archived,completed'],
        ];
    }

    public function toData(): ProjectData
    {
        return ProjectData::fromArray($this->validated());
    }
}
