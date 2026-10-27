<?php

declare(strict_types=1);

namespace App\Http\Requests\Projects;

use App\DTOs\Projects\ProjectData;
use App\Enums\ProjectStatus;
use App\Rules\HexColorRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'color' => ['sometimes', 'required', 'string', new HexColorRule],
            'estimated_hours' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'client_id' => [
                'nullable',
                'integer',
                Rule::exists('clients', 'id')->where('user_id', $this->user()?->id),
            ],
            'status' => ['sometimes', Rule::enum(ProjectStatus::class)],
        ];
    }

    public function toDTO(): ProjectData
    {
        return ProjectData::fromArray($this->validated());
    }
}
