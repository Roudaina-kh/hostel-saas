<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomPrice;
use Illuminate\Http\Request;

class PricingController extends Controller
{
    private function hostelId(): int
    {
        return session('hostel_id');
    }

    public function index()
    {
        $prices = RoomPrice::where('hostel_id', $this->hostelId())
            ->with('room')->latest()->get();
        $rooms = Room::where('hostel_id', $this->hostelId())->get();

        return view('pricing.index', compact('prices', 'rooms'));
    }

    public function create()
    {
        $rooms = Room::where('hostel_id', $this->hostelId())->get();
        return view('pricing.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePrice($request);
        $data['hostel_id'] = $this->hostelId();

        // Si is_active, désactiver les autres prix de cette chambre
        if ($data['is_active']) {
            RoomPrice::where('room_id', $data['room_id'])
                ->where('hostel_id', $this->hostelId())
                ->update(['is_active' => false]);
        }

        RoomPrice::create($data);

        return redirect()->route('pricing.index')
            ->with('success', 'Tarif ajouté avec succès.');
    }

    public function edit(RoomPrice $pricing)
    {
        $this->authorizePrice($pricing);
        $rooms = Room::where('hostel_id', $this->hostelId())->get();
        return view('pricing.edit', compact('pricing', 'rooms'));
    }

    public function update(Request $request, RoomPrice $pricing)
    {
        $this->authorizePrice($pricing);
        $data = $this->validatePrice($request);

        if ($data['is_active']) {
            RoomPrice::where('room_id', $pricing->room_id)
                ->where('hostel_id', $this->hostelId())
                ->where('id', '!=', $pricing->id)
                ->update(['is_active' => false]);
        }

        $pricing->update($data);

        return redirect()->route('pricing.index')
            ->with('success', 'Tarif mis à jour.');
    }

    public function activate(RoomPrice $pricing)
    {
        $this->authorizePrice($pricing);

        // Désactiver tous les autres prix de cette chambre
        RoomPrice::where('room_id', $pricing->room_id)
            ->where('hostel_id', $this->hostelId())
            ->update(['is_active' => false]);

        $pricing->update(['is_active' => true]);

        return response()->json(['success' => true]);
    }

public function destroy(RoomPrice $pricing)
{
    $this->authorizePrice($pricing);
    $pricing->delete();

    return redirect()->route('pricing.index')
        ->with('success', 'Tarif supprimé avec succès.');
}

    private function validatePrice(Request $request): array
    {
        return $request->validate([
            'room_id'      => 'required|exists:rooms,id',
            'price_amount' => 'required|numeric|min:0',
            'currency'     => 'required|string|max:10',
            'valid_from'   => 'nullable|date',
            'valid_to'     => 'nullable|date|after_or_equal:valid_from',
            'is_active'    => 'boolean',
        ]);
    }

    private function authorizePrice(RoomPrice $pricing): void
    {
        abort_unless($pricing->hostel_id === $this->hostelId(), 403);
    }
}