<?php

namespace App\Http\Requests;

use App\Enums\ExpenseCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // L'authorization se fait dans le controller (multi-tenant guard)
    }

    public function rules(): array
    {
        return [
            'payer_name'   => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', Rule::in(ExpenseCategory::values())],
            'label'        => ['required', 'string', 'max:255'],
            'amount'       => ['required', 'numeric', 'min:0', 'max:9999999.999'],
            'currency'     => ['required', 'string', Rule::in(['TND', 'EUR', 'USD'])],
            'expense_date' => ['required', 'date', 'before_or_equal:today'],
            'note'         => ['nullable', 'string', 'max:2000'],
            'password'     => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'payer_name.required'   => 'Le nom du payeur est obligatoire.',
            'category.required'     => 'La catégorie est obligatoire.',
            'category.in'           => 'Catégorie invalide.',
            'label.required'        => 'Le libellé est obligatoire.',
            'amount.required'       => 'Le montant est obligatoire.',
            'amount.numeric'        => 'Le montant doit être un nombre.',
            'amount.min'            => 'Le montant ne peut pas être négatif.',
            'currency.in'           => 'Devise invalide (TND, EUR ou USD).',
            'expense_date.required' => 'La date est obligatoire.',
            'expense_date.before_or_equal' => 'La date ne peut pas être dans le futur.',
            'password.required'     => 'Le mot de passe est requis pour confirmer.',
        ];
    }
}