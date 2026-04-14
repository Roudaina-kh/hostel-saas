<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExtraStockMovementRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'movement_type' => ['required', 'in:initial,purchase,adjustment_in,adjustment_out,damage,loss,return'],
            'quantity'      => ['required', 'integer', 'min:1'],
            'note'          => ['nullable', 'string'],
        ];
    }
}