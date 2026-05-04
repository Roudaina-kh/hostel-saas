<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Http\Requests\StoreExchangeRateRequest;

class ExchangeRateController extends Controller
{
    public function index()
    {
        $hostelId = (int) session('current_hostel_id');

        $exchangeRates = ExchangeRate::where('hostel_id', $hostelId)
            ->with('creator')
            ->latest()
            ->get();

        // Taux actifs : dernier taux par devise
        $activeRates = ExchangeRate::where('hostel_id', $hostelId)
            ->latest()
            ->get()
            ->unique('currency');

        return view('exchange-rates.index', compact('exchangeRates', 'activeRates'));
    }

    public function create()
    {
        $hostelId = (int) session('hostel_id');

        // Utilisateurs du hostel pour le champ created_by
        $users = \App\Models\User::whereHas('hostels', fn($q) =>
            $q->where('hostels.id', $hostelId)
        )->get();

        return view('exchange-rates.create', compact('users'));
    }

    public function store(StoreExchangeRateRequest $request)
    {
        $data = $request->validated();
        $data['hostel_id'] = session('hostel_id');

        // Règle métier : on ne modifie jamais un taux existant
        // Chaque appel crée une nouvelle ligne (historique immuable)
        ExchangeRate::create($data);

        return redirect()
            ->route('exchange-rates.index')
            ->with('success', 'Taux de change ajouté. Taux actif mis à jour.');
    }

    public function show(ExchangeRate $exchangeRate)
    {
        $this->authorizeRate($exchangeRate);
        return view('exchange-rates.show', compact('exchangeRate'));
    }

    // 🛡️ Sécurité : isolation hostel
    private function authorizeRate(ExchangeRate $rate): void
    {
        abort_unless($rate->hostel_id === (int) session('hostel_id'), 403);
    }
}