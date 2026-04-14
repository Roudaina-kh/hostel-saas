<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $hostelId = session('hostel_id');

        $rooms = Room::where('hostel_id', $hostelId)
            ->withCount('beds')
            ->latest()
            ->get();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

public function store(StoreRoomRequest $request)
{
    $data = $request->validated();
    $data['hostel_id'] = session('hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');

    Room::create($data);

    return redirect()->route('rooms.index')->with('success', 'Chambre ajoutée.');
}
    public function edit(Room $room)
    {
        $this->authorizeRoom($room);
        return view('rooms.edit', compact('room'));
    }

    public function update(UpdateRoomRequest $request, Room $room)
    {
        $this->authorizeRoom($room);
        $room->update($request->validated());

        return redirect()->route('rooms.index')
            ->with('success', 'Chambre mise à jour.');
    }

    public function destroy(Room $room)
    {
        $this->authorizeRoom($room);

        // Sécurité : empêche la suppression si des lits existent
        if ($room->beds()->exists()) {
            return back()->withErrors(['error' => 'Impossible de supprimer une chambre qui contient des lits.']);
        }

        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Chambre supprimée.');
    }

    private function authorizeRoom(Room $room): void
    {
        abort_unless($room->hostel_id === (int) session('hostel_id'), 403);
    }
}