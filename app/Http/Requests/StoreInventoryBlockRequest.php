<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryBlockRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // hostel_id injecté depuis session() dans le controller
            'blockable_type' => ['required', 'in:room,bed,tent_space'],
            'blockable_id'   => ['required', 'integer'],
            'block_type'     => ['required', 'in:maintenance,manual_block'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'reason'         => ['nullable', 'string'],
            'note'           => ['nullable', 'string'],
        ];
    }
}