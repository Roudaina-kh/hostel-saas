<?php

namespace App\Http\Requests;

use App\Enums\ExpenseCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
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
}