<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use App\Http\Requests\StoreExchangeRateRequest;
use Illuminate\Support\Facades\Auth;

class ManagerExchangeRateController extends Controller
{
    private function hostelId(): int
    {
        return (int) session('staff_hostel_id');
    }

    public function index()
    {
        $hostelId = $this->hostelId();

        $exchangeRates = ExchangeRate::where('hostel_id', $hostelId)
            ->with('creator')
            ->latest()
            ->get();

        $activeRates = ExchangeRate::where('hostel_id', $hostelId)
            ->latest()
            ->get()
            ->unique('currency');

        return view('exchange-rates.index', compact('exchangeRates', 'activeRates'));
    }

    public function create()
    {
        $hostelId = $this->hostelId();

        $users = \App\Models\User::whereHas('hostels', fn($q) =>
            $q->where('hostels.id', $hostelId)
        )->get();

        return view('exchange-rates.create', compact('users'));
    }

    public function store(StoreExchangeRateRequest $request)
    {
        $data = $request->validated();
        $data['hostel_id'] = $this->hostelId();

        ExchangeRate::create($data);

        return redirect()
            ->route('manager.exchange-rates.index')
            ->with('success', 'Taux de change ajouté.');
    }

    public function show(ExchangeRate $exchangeRate)
    {
        abort_unless($exchangeRate->hostel_id === $this->hostelId(), 403);
        return view('exchange-rates.show', compact('exchangeRate'));
    }
}