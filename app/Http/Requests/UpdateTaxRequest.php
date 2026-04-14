<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string'],
            'type'        => ['required', 'in:percentage,fixed_amount,fixed_per_night,fixed_per_person_per_night'],
            'amount'      => ['required', 'numeric', 'min:0'],
            'is_enabled'  => ['boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}