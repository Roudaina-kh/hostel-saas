<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Room;
use Illuminate\Http\Request;

class BedController extends Controller
{
    private function hostelId(): int
    {
        return session('hostel_id');
    }

    public function index()
    {
        $beds = Bed::where('hostel_id', $this->hostelId())
            ->with('room')->latest()->get();
        $rooms = Room::where('hostel_id', $this->hostelId())
            ->where('type', 'dormitory')->get();

        return view('beds.index', compact('beds', 'rooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name'    => 'required|string|max:100',
        ]);

        // Vérifier que la chambre appartient au hostel
        $room = Room::findOrFail($data['room_id']);
        abort_unless($room->hostel_id === $this->hostelId(), 403);

        Bed::create([
            'hostel_id' => $this->hostelId(),
            'room_id'   => $data['room_id'],
            'name'      => $data['name'],
        ]);

        return redirect()->route('beds.index')
            ->with('success', 'Lit ajouté avec succès.');
    }

    public function update(Request $request, Bed $bed)
    {
        $this->authorizeBed($bed);
        $bed->update($request->validate([
            'name' => 'required|string|max:100',
        ]));

        return redirect()->route('beds.index')
            ->with('success', 'Lit mis à jour.');
    }

    public function toggleMaintenance(Bed $bed)
    {
        $this->authorizeBed($bed);
        $bed->update(['maintenance' => ! $bed->maintenance]);

        return response()->json([
            'success'     => true,
            'maintenance' => $bed->maintenance,
        ]);
    }

public function destroy(Bed $bed)
{
    $this->authorizeBed($bed);
    $bed->delete();

    return redirect()->route('beds.index')
        ->with('success', 'Lit supprimé avec succès.');
}

    private function authorizeBed(Bed $bed): void
    {
        abort_unless($bed->hostel_id === $this->hostelId(), 403);
    }
}