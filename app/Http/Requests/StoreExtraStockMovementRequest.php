<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class StoreExtraStockMovementRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // hostel_id injecté depuis session() dans le controller
            'extra_id'      => ['required', 'exists:extras,id'],
            'movement_type' => ['required', 'in:initial,purchase,adjustment_in,adjustment_out,damage,loss,return'],
            'quantity'      => ['required', 'integer', 'min:1'],
            'note'          => ['nullable', 'string'],
            'created_by'    => ['required', 'exists:users,id'],
            'password'      => ['required', 'string'],
        ];
    }

    // 🛡️ Outil de sécurité : vérifie le mot de passe de l'utilisateur sélectionné
    // Garantit que seul l'utilisateur lui-même peut valider le mouvement de stock
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = User::find($this->created_by);

            if (! $user || ! Hash::check($this->password, $user->password)) {
                $validator->errors()->add('password', 'Mot de passe incorrect.');
            }
        });
    }
}