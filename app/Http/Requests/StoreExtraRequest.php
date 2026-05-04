<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExtraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hostelId = session('hostel_id');

        return [
            'name'                  => [
                'required',
                'string',
                'max:100',
                Rule::unique('extras', 'name')->where('hostel_id', $hostelId),
            ],
            'description'           => ['nullable', 'string', 'max:500'],
            'stock_mode'            => ['required', 'in:unlimited,consumable,rentable'],
            'stock_quantity'        => ['nullable', 'integer', 'min:0'],
            'stock_alert_threshold' => ['nullable', 'integer', 'min:0'],
            'is_enabled'            => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'extra est obligatoire.',
            'name.unique'   => 'Un extra avec ce nom existe déjà dans cet hostel. Veuillez choisir un autre nom.',
            'stock_mode.required' => 'Le mode de stock est obligatoire.',
            'stock_mode.in'       => 'Mode de stock invalide.',
            'stock_quantity.min'  => 'Le stock ne peut pas être négatif.',
            'stock_alert_threshold.min' => 'Le seuil d\'alerte ne peut pas être négatif.',
        ];
    }
}