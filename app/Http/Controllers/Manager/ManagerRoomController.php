<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerRoomController extends Controller
{
    private function hostelId(): int
    {
        return session('staff_hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');
    }

    private function checkPermission(): void
    {
        $user = Auth::guard('staff')->user();
        abort_unless($user->hasPermission('can_manage_rooms', $this->hostelId()), 403, 'Permission refusée.');
    }

    public function index()
    {
        $rooms = Room::where('hostel_id', $this->hostelId())
            ->withCount('beds')
            ->with('activePrice')
            ->latest()->get();

        return view('manager.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $this->checkPermission();
        return view('manager.rooms.create');
    }

    public function store(Request $request)
    {
        $this->checkPermission();

        $data = $request->validate([
            'name'         => 'required|string|max:150',
            'type'         => 'required|in:private,dormitory',
            'min_capacity' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'status'       => 'required|in:active,maintenance,inactive',
            'description'  => 'nullable|string',
        ]);

        $data['hostel_id'] = $this->hostelId();
        Room::create($data);

        return redirect()->route('manager.rooms.index')
            ->with('success', 'Chambre créée avec succès.');
    }

    public function edit(Room $room)
    {
        $this->checkPermission();
        abort_unless($room->hostel_id === $this->hostelId(), 403);
        return view('manager.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $this->checkPermission();
        abort_unless($room->hostel_id === $this->hostelId(), 403);

        $room->update($request->validate([
            'name'         => 'required|string|max:150',
            'type'         => 'required|in:private,dormitory',
            'min_capacity' => 'required|integer|min:1',
            'max_capacity' => 'required|integer|min:1',
            'status'       => 'required|in:active,maintenance,inactive',
            'description'  => 'nullable|string',
        ]));

        return redirect()->route('manager.rooms.index')
            ->with('success', 'Chambre mise à jour.');
    }

    public function destroy(Room $room)
    {
        $this->checkPermission();
        abort_unless($room->hostel_id === $this->hostelId(), 403);
        $room->delete();

        return redirect()->route('manager.rooms.index')
            ->with('success', 'Chambre supprimée.');
    }
}