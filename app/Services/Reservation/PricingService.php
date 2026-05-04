<?php

namespace App\Services\Reservation;

class PricingService
{
    /**
     * Calcule le prix TND depuis le prix saisi + devise + taux.
     *
     * RÈGLE CRITIQUE :
     * - price_input + currency = référence commerciale (immuable)
     * - price_tnd = conversion interne (snapshot)
     * - Utiliser SELL RATE pour les réservations en devise étrangère
     */
    public function computePriceTnd(float $priceInput, string $currency, float $exchangeRate): float
    {
        if (strtoupper($currency) === 'TND') {
            return $priceInput;
        }

        return round($priceInput * $exchangeRate, 3);
    }

    /**
     * Calcule les totaux d'une réservation.
     */
    public function computeTotals(array $guestsData): array
    {
        $totalTnd = 0.0;
        $totalEur = 0.0;
        $totalUsd = 0.0;

        foreach ($guestsData as $guest) {
            $totalTnd += (float) ($guest['price_tnd'] ?? 0);

            $currency = strtoupper($guest['currency'] ?? 'TND');

            if ($currency === 'EUR') {
                $totalEur += (float) ($guest['price_input'] ?? 0);
            }

            if ($currency === 'USD') {
                $totalUsd += (float) ($guest['price_input'] ?? 0);
            }
        }

        return [
            'total_price_tnd' => round($totalTnd, 3),
            'total_price_eur' => $totalEur > 0 ? round($totalEur, 3) : null,
            'total_price_usd' => $totalUsd > 0 ? round($totalUsd, 3) : null,
        ];
    }
}