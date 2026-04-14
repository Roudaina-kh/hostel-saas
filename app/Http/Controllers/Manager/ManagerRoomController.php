<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerRoomController extends Controller
{
    private function getHostelId(): int
    {
        return (int) session('staff_hostel_id');
    }

    private function getManager()
    {
        return Auth::guard('user')->user();
    }

    private function checkPermission(): void
    {
        $user     = $this->getManager();
        $hostelId = $this->getHostelId();

        $pivot = $user->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->first();

        abort_unless($pivot && $pivot->pivot->role === 'manager', 403, 'Permission refusée.');
    }

    public function index()
    {
        $hostelId = $this->getHostelId();

        $rooms = Room::where('hostel_id', $hostelId)
            ->withCount('beds')
            ->with('activePrice')
            ->latest()
            ->get();

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

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:private,dormitory',
            'max_capacity' => 'required|integer|min:1',
            'description'  => 'nullable|string|max:1000',
            'is_enabled'   => 'nullable|boolean',
        ]);

        $exists = Room::where('hostel_id', $this->getHostelId())
            ->where('name', $validated['name'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Une chambre avec ce nom existe déjà.'])
                ->withInput();
        }

        Room::create([
            'hostel_id'    => $this->getHostelId(),
            'name'         => $validated['name'],
            'type'         => $validated['type'],
            'max_capacity' => $validated['max_capacity'],
            'description'  => $validated['description'] ?? null,
            'is_enabled'   => $request->boolean('is_enabled'),
        ]);

        return redirect()->route('manager.rooms.index')
            ->with('success', 'Chambre créée avec succès.');
    }

    public function edit(Room $room)
    {
        $this->checkPermission();
        abort_unless($room->hostel_id === $this->getHostelId(), 403);

        return view('manager.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $this->checkPermission();
        abort_unless($room->hostel_id === $this->getHostelId(), 403);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:private,dormitory',
            'max_capacity' => 'required|integer|min:1',
            'description'  => 'nullable|string|max:1000',
            'is_enabled'   => 'nullable|boolean',
        ]);

        $exists = Room::where('hostel_id', $this->getHostelId())
            ->where('name', $validated['name'])
            ->where('id', '!=', $room->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors(['name' => 'Une chambre avec ce nom existe déjà.'])
                ->withInput();
        }

        $room->update([
            'name'         => $validated['name'],
            'type'         => $validated['type'],
            'max_capacity' => $validated['max_capacity'],
            'description'  => $validated['description'] ?? null,
            'is_enabled'   => $request->boolean('is_enabled'),
        ]);

        return redirect()->route('manager.rooms.index')
            ->with('success', 'Chambre mise à jour.');
    }

    public function destroy(Room $room)
    {
        $this->checkPermission();
        abort_unless($room->hostel_id === $this->getHostelId(), 403);
        $room->delete();

        return redirect()->route('manager.rooms.index')
            ->with('success', 'Chambre supprimée.');
    }
}