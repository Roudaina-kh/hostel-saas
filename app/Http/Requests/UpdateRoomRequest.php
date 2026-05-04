<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $hostelId = session('hostel_id');
        $roomId   = $this->route('room')?->id;

        return [
            'name'         => [
                'required',
                'string',
                'max:100',
                Rule::unique('rooms', 'name')
                    ->where('hostel_id', $hostelId)
                    ->ignore($roomId),
            ],
            'type'         => ['required', 'in:private,dormitory'],
            'max_capacity' => ['required', 'integer', 'min:1'],
            'is_enabled'   => ['boolean'],
            'description'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Le nom de la chambre est obligatoire.',
            'name.unique'   => 'Une chambre avec ce nom existe déjà dans cet hostel. Veuillez choisir un autre nom.',
            'type.required' => 'Le type de chambre est obligatoire.',
            'type.in'       => 'Le type doit être "private" ou "dormitory".',
            'max_capacity.required' => 'La capacité maximale est obligatoire.',
            'max_capacity.min'      => 'La capacité doit être au moins 1.',
        ];
    }
}