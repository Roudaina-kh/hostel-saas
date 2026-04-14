<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBedRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'room_id'    => ['required', 'exists:rooms,id'],
            'name'       => ['required', 'string'],
            'is_enabled' => ['boolean'],
        ];
    }
}