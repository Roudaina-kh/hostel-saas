<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('owner')->check() || auth('user')->check();
    }

    public function rules(): array
    {
        return [
            'start_date'   => ['required', 'date'],
            'end_date'     => ['required', 'date', 'after:start_date'],
            'nights'       => ['required', 'integer', 'min:1'],
            'total_guests' => ['required', 'integer', 'min:1'],
            'status'       => ['required', 'in:pending,confirmed'],
            'source'       => ['nullable', 'string', 'max:100'],
            'notes'        => ['nullable', 'string', 'max:2000'],
            'password'     => ['required', 'string'],
            'guests_data'  => ['required', 'json'],
            'nationality' => 'nullable|string|max:100',
            // ↓ removed: added_by_user_id — l'utilisateur est détecté
            //   automatiquement côté controller (owner ou user connecté)
        ];
    }

    public function messages(): array
    {
        return [
            'start_date.required'  => "La date d'arrivée est obligatoire.",
            'end_date.required'    => 'La date de départ est obligatoire.',
            'end_date.after'       => "La date de départ doit être après la date d'arrivée.",
            'nights.min'           => 'La réservation doit durer au moins 1 nuit.',
            'total_guests.min'     => 'Au moins 1 personne est requise.',
            'status.in'            => 'Statut invalide.',
            'password.required'    => 'Le mot de passe est obligatoire.',
            'guests_data.required' => 'Les données des guests sont obligatoires.',
            'guests_data.json'     => 'Format de données invalide.',
        ];
    }
}