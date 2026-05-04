<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class StoreExtraStockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'movement_type' => ['required', 'in:initial,purchase,adjustment_in,adjustment_out,damage,loss,return'],
            'quantity'      => ['required', 'integer', 'min:1'],
            'note'          => ['nullable', 'string', 'max:500'],
            'created_by'    => ['required', 'exists:users,id'],
            'password'      => ['required', 'string'],
        ];
    }

    /**
     * Vérifie que le mot de passe correspond à l'utilisateur sélectionné
     * et qu'il est bien autorisé sur ce hostel.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->any()) {
                    return;
                }

                $hostelId = session('hostel_id');
                $userId   = (int) $this->input('created_by');

                /** @var User|null $user */
                $user = User::find($userId);

                // Vérification mot de passe
                if (! $user || ! Hash::check($this->input('password'), $user->password)) {
                    $validator->errors()->add('password', 'Mot de passe incorrect pour cet utilisateur.');
                    return;
                }

                // Vérification autorisation hostel
                $isAuthorized = $user->hostels()
                    ->where('hostels.id', $hostelId)
                    ->exists();

                if (! $isAuthorized) {
                    $validator->errors()->add('created_by', 'Cet utilisateur n\'est pas autorisé sur ce hostel.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'movement_type.required' => 'Le type de mouvement est obligatoire.',
            'movement_type.in'       => 'Type de mouvement invalide.',
            'quantity.required'      => 'La quantité est obligatoire.',
            'quantity.min'           => 'La quantité doit être au moins 1.',
            'created_by.required'    => 'Veuillez sélectionner un utilisateur.',
            'created_by.exists'      => 'Utilisateur introuvable.',
            'password.required'      => 'Le mot de passe est obligatoire.',
        ];
    }
}