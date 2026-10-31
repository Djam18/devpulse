<?php

declare(strict_types=1);

namespace App\Http\Requests\Clients;

use App\DTOs\Clients\ClientData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }

    public function toData(): ClientData
    {
        return ClientData::fromArray($this->validated());
    }
}
