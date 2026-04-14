<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // hostel_id injecté depuis session() dans le controller
            'name'         => ['required', 'string', 'max:255'],
            'type'         => ['required', 'in:private,dormitory'],
            'max_capacity' => ['required', 'integer', 'min:1'],
            'is_enabled'   => ['boolean'],
            'description'  => ['nullable', 'string'],
        ];
    }
}