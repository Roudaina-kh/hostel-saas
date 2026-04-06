<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerPricingController extends Controller
{
    private function hostelId(): int
    {
        return session('staff_hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');
    }



    public function index()
    {
        $prices = RoomPrice::where('hostel_id', $this->hostelId())
            ->with('room')->latest()->get();
        $rooms = Room::where('hostel_id', $this->hostelId())->get();

        return view('manager.pricing.index', compact('prices', 'rooms'));
    }

    public function create()
    {
        $rooms = Room::where('hostel_id', $this->hostelId())->get();
        return view('manager.pricing.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePrice($request);
        $data['hostel_id'] = $this->hostelId();

        if ($data['is_active']) {
            RoomPrice::where('room_id', $data['room_id'])
                ->where('hostel_id', $this->hostelId())
                ->update(['is_active' => false]);
        }

        RoomPrice::create($data);

        return redirect()->route('manager.pricing.index')
            ->with('success', 'Tarif ajouté avec succès.');
    }

    public function edit(RoomPrice $pricing)
    {
        $this->authorizePrice($pricing);
        $rooms = Room::where('hostel_id', $this->hostelId())->get();
        return view('manager.pricing.edit', compact('pricing', 'rooms'));
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

        return redirect()->route('manager.pricing.index')
            ->with('success', 'Tarif mis à jour.');
    }

    public function activate(RoomPrice $pricing)
    {
        $this->authorizePrice($pricing);

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

        return redirect()->route('manager.pricing.index')
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
