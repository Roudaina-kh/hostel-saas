<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    private function hostelId(): int
    {
        return session('hostel_id');
    }

    public function index()
    {
        $rooms = Room::where('hostel_id', $this->hostelId())
            ->withCount('beds')
            ->with('activePrice')
            ->latest()->get();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRoom($request);
        $data['hostel_id'] = $this->hostelId();
        Room::create($data);

        return redirect()->route('rooms.index')
            ->with('success', 'Chambre créée avec succès.');
    }

    public function edit(Room $room)
    {
        $this->authorizeRoom($room);
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $this->authorizeRoom($room);
        $room->update($this->validateRoom($request));

        return redirect()->route('rooms.index')
            ->with('success', 'Chambre mise à jour.');
    }

public function destroy(Room $room)
{
    $this->authorizeRoom($room);
    $room->delete();

    return redirect()->route('rooms.index')
        ->with('success', 'Chambre supprimée avec succès.');
}

    private function validateRoom(Request $request): array
    {
        return $request->validate([
            'name'         => 'required|string|max:150',
            'type'         => 'required|in:private,dormitory',
            'min_capacity' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'status'       => 'required|in:active,maintenance,inactive',
            'description'  => 'nullable|string',
        ]);
    }

    private function authorizeRoom(Room $room): void
    {
        abort_unless($room->hostel_id === $this->hostelId(), 403);
    }
    
}