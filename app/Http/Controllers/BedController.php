<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Room;
use App\Http\Requests\StoreBedRequest;
use App\Http\Requests\UpdateBedRequest;

class BedController extends Controller
{
    public function index()
    {
        $hostelId = session('hostel_id');

        // ✅ Chambres dortoirs avec leurs lits (eager loading)
        $dormitories = Room::where('hostel_id', $hostelId)
            ->where('type', 'dormitory')
            ->with(['beds' => fn ($q) => $q->latest()])
            ->withCount('beds')
            ->orderBy('name')
            ->get();

        // ⚠️ Chambres en surcapacité (legacy data)
        $overCapacity = $dormitories
            ->filter(fn ($r) => $r->beds_count > $r->max_capacity)
            ->values();

        return view('beds.index', compact('dormitories', 'overCapacity'));
    }

    public function store(StoreBedRequest $request)
    {
        $data = $request->validated();

        Bed::create([
            'room_id' => $data['room_id'],
            'name'    => $data['name'],
        ]);

        return redirect()->route('beds.index')->with('success', 'Lit ajouté.');
    }

    public function update(UpdateBedRequest $request, Bed $bed)
    {
        $this->authorizeBed($bed);
        $bed->update($request->validated());

        return redirect()->route('beds.index')->with('success', 'Lit mis à jour.');
    }

    public function toggleEnabled(Bed $bed)
    {
        $this->authorizeBed($bed);
        $bed->update(['is_enabled' => ! $bed->is_enabled]);

        return response()->json([
            'success'    => true,
            'is_enabled' => $bed->is_enabled,
        ]);
    }

    public function destroy(Bed $bed)
    {
        $this->authorizeBed($bed);
        $bed->delete();

        return redirect()->route('beds.index')->with('success', 'Lit supprimé.');
    }

    private function authorizeBed(Bed $bed): void
    {
        abort_unless(
            $bed->room->hostel_id === (int) session('hostel_id'),
            403
        );
    }
}