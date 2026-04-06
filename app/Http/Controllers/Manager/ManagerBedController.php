<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Bed;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerBedController extends Controller
{
    private function hostelId(): int
    {
        return session('staff_hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');
    }



    public function index()
    {
        $beds = Bed::where('hostel_id', $this->hostelId())
            ->with('room')->latest()->get();
        $rooms = Room::where('hostel_id', $this->hostelId())
            ->where('type', 'dormitory')->get();

        return view('manager.beds.index', compact('beds', 'rooms'));
    }

    public function store(Request $request)
    {
        
        $data = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'name'    => 'required|string|max:100',
        ]);

        $room = Room::findOrFail($data['room_id']);
        abort_unless($room->hostel_id === $this->hostelId(), 403);

        Bed::create([
            'hostel_id' => $this->hostelId(),
            'room_id'   => $data['room_id'],
            'name'      => $data['name'],
        ]);

        return redirect()->route('manager.beds.index')
            ->with('success', 'Lit ajouté avec succès.');
    }

    public function update(Request $request, Bed $bed)
    {
        $this->authorizeBed($bed);

        $bed->update($request->validate([
            'name' => 'required|string|max:100',
        ]));

        return redirect()->route('manager.beds.index')
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

        return redirect()->route('manager.beds.index')
            ->with('success', 'Lit supprimé avec succès.');
    }

    private function authorizeBed(Bed $bed): void
    {
        abort_unless($bed->hostel_id === $this->hostelId(), 403);
    }
}
