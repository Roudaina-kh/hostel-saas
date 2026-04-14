<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'pricing_mode' => ['required', 'in:per_room,per_bed,per_person,per_unit,per_night,per_person_per_night'],
            'price_ht'     => ['required', 'numeric', 'min:0'],
            'price_ttc'    => ['required', 'numeric', 'min:0'],
            'valid_from'   => ['required', 'date'],
            'valid_to'     => ['nullable', 'date', 'after_or_equal:valid_from'],
            'tax_ids'      => ['nullable', 'array'],
            'tax_ids.*'    => ['exists:taxes,id'],
        ];
    }
}