<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExtraRequest extends FormRequest  // ← nom corrigé
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                  => ['required', 'string', 'max:150'],
            'description'           => ['nullable', 'string'],
            'stock_mode'            => ['required', 'in:unlimited,consumable,rentable'],
            'stock_quantity'        => ['nullable', 'integer', 'min:0'],
            'stock_alert_threshold' => ['nullable', 'integer', 'min:0'],
            'is_enabled'            => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_enabled' => $this->boolean('is_enabled'),
        ]);
    }
}