<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTentSpaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hostelId = session('hostel_id');

        return [
            'name'        => [
                'required',
                'string',
                'max:100',
                Rule::unique('tent_spaces', 'name')->where('hostel_id', $hostelId),
            ],
            'max_tents'   => ['nullable', 'integer', 'min:1'],
            'max_persons' => ['nullable', 'integer', 'min:1'],
            'is_enabled'  => ['boolean'],
            'description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de l\'espace tente est obligatoire.',
            'name.unique'   => 'Un espace tente avec ce nom existe déjà dans cet hostel. Veuillez choisir un autre nom.',
            'max_tents.min'   => 'Le nombre de tentes doit être au moins 1.',
            'max_persons.min' => 'Le nombre de personnes doit être au moins 1.',
        ];
    }
}