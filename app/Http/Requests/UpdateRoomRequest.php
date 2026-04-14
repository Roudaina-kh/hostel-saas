<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => 'required|string|max:150',
            'type'         => 'required|in:dormitory,private',
            'max_capacity' => 'required|integer|min:1',
            'description'  => 'nullable|string',
            'is_enabled'   => 'boolean', // ← is_enabled, pas is_active
        ];
    }

    protected function prepareForValidation(): void
    {
        // La checkbox non cochée n'envoie rien → on force false
        $this->merge([
            'is_enabled' => $this->boolean('is_enabled'),
        ]);
    }
}