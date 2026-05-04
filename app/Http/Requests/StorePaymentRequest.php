<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use App\Models\ReservationPerson;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // L'autorisation hostel est vérifiée dans le controller
    }

    public function rules(): array
    {
        $hostelId = session('hostel_id') ?? session('staff_hostel_id');
        return [
            // reservation_id doit appartenir à l'hostel en session
            'reservation_id' => [
                'required',
                'integer',
                Rule::exists('reservations', 'id')->where('hostel_id', $hostelId),
            ],

            // Si fourni, la personne doit appartenir à la réservation
            'reservation_person_id' => [
                'nullable',
                'integer',
                // Validation croisée dans after()
            ],

            'amount_input'     => ['required', 'numeric', 'min:0.001', 'max:999999.999'],
            'currency'         => ['required', Rule::in(['TND', 'EUR', 'USD'])],
            'exchange_rate'    => ['required', 'numeric', 'min:0.0001', 'max:9999.9999'],
            'payment_method'   => ['required', Rule::in(['cash', 'card', 'transfer', 'other'])],
            'status'           => ['required', Rule::in(['unpaid', 'partial', 'paid'])],
            'received_by'      => ['nullable', 'string', 'max:100'],
            'note'             => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Validation croisée : reservation_person_id appartient bien à la réservation
     */
    public function after(): array
    {
        return [
            function ($validator) {
                $personId = $this->integer('reservation_person_id');
                $reservationId = $this->integer('reservation_id');

                if ($personId && $reservationId) {
                    $exists = ReservationPerson::where('id', $personId)
                        ->where('reservation_id', $reservationId)
                        ->exists();

                    if (! $exists) {
                        $validator->errors()->add(
                            'reservation_person_id',
                            'Cette personne n\'appartient pas à la réservation sélectionnée.'
                        );
                    }
                }
            },
        ];
    }

    /**
     * Calcul automatique du montant TND avant validation
     * (appelé depuis le controller)
     */
    public function amountTnd(): float
    {
        return round(
            $this->float('amount_input') * $this->float('exchange_rate'),
            3
        );
    }

    public function messages(): array
    {
        return [
            'reservation_id.exists'  => 'La réservation sélectionnée est invalide.',
            'amount_input.min'       => 'Le montant doit être supérieur à 0.',
            'exchange_rate.min'      => 'Le taux de change doit être positif.',
            'currency.in'            => 'Devise non supportée (TND, EUR, USD).',
            'payment_method.in'      => 'Mode de paiement invalide.',
            'status.in'              => 'Statut invalide.',
        ];
    }
}