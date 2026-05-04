<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // On ne permet PAS de changer reservation_id ni reservation_person_id après création
            'amount_input'   => ['required', 'numeric', 'min:0.001', 'max:999999.999'],
            'currency'       => ['required', Rule::in(['TND', 'EUR', 'USD'])],
            'exchange_rate'  => ['required', 'numeric', 'min:0.0001', 'max:9999.9999'],
            'payment_method' => ['required', Rule::in(['cash', 'card', 'transfer', 'other'])],
            'status'         => ['required', Rule::in(['unpaid', 'partial', 'paid'])],
            'received_by'    => ['nullable', 'string', 'max:100'],
            'note'           => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function amountTnd(): float
    {
        return round(
            $this->float('amount_input') * $this->float('exchange_rate'),
            3
        );
    }
}