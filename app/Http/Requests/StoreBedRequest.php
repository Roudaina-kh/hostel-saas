<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreBedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => [
                'required',
                'integer',
                Rule::exists('rooms', 'id')->where(
                    fn ($q) => $q->where('hostel_id', session('hostel_id'))
                ),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('beds', 'name')->where(
                    fn ($q) => $q->where('room_id', $this->input('room_id'))
                ),
            ],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $room = Room::find($this->input('room_id'));

                if ($room && $room->type !== 'dormitory') {
                    $validator->errors()->add(
                        'room_id',
                        'Les lits ne peuvent être créés que dans une chambre de type dortoir.'
                    );
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'La chambre est obligatoire.',
            'room_id.exists'   => 'La chambre sélectionnée est invalide.',
            'name.required'    => 'Le nom du lit est obligatoire.',
            'name.unique'      => 'Un lit avec ce nom existe déjà dans cette chambre.',
        ];
    }
}