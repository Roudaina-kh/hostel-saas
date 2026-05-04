<?php

namespace App\Http\Requests;

use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomId = $this->input('room_id');

        return [
            'room_id' => [
                'required',
                'exists:rooms,id',
                // La room doit être de type dormitory
                function ($attribute, $value, $fail) {
                    $room = Room::find($value);
                    if (! $room || $room->type !== 'dormitory') {
                        $fail('Les lits ne peuvent être créés que dans une chambre de type dortoir.');
                    }
                    // La room doit appartenir au hostel courant
                    if ($room && $room->hostel_id !== (int) session('hostel_id')) {
                        $fail('Cette chambre n\'appartient pas à votre hostel.');
                    }
                },
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('beds', 'name')->where('room_id', $roomId),
            ],
            'is_enabled' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Veuillez sélectionner une chambre.',
            'room_id.exists'   => 'Chambre introuvable.',
            'name.required'    => 'Le nom du lit est obligatoire.',
            'name.unique'      => 'Un lit avec ce nom existe déjà dans cette chambre. Veuillez choisir un autre nom.',
        ];
    }
}