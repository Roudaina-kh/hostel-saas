<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExchangeRateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // hostel_id injecté depuis session() dans le controller
            'currency' => [
                'required',
                'string',
                'max:10',
                'in:EUR,USD,GBP,MAD,DZD,EGP,LYD,SAR,AED,QAR,CHF,CAD,AUD,JPY,CNY',
            ],

            'buy_rate_to_tnd' => [
                'required',
                'numeric',
                'min:0',
            ],

            // 🛡️ Sécurité métier : sell >= buy (l'hostel ne peut pas vendre moins cher qu'il achète)
            'sell_rate_to_tnd' => [
                'required',
                'numeric',
                'min:0',
                'gte:buy_rate_to_tnd',
            ],

            'created_by' => [
                'nullable',
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'sell_rate_to_tnd.gte' => 'Le taux de vente doit être supérieur ou égal au taux d\'achat.',
        ];
    }
}