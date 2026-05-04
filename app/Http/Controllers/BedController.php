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

        $beds = Bed::whereHas('room', fn ($q) => $q->where('hostel_id', $hostelId))
            ->with('room')
            ->latest()
            ->get();

        // Seulement les dortoirs pour le formulaire d'ajout
        $rooms = Room::where('hostel_id', $hostelId)
            ->where('type', 'dormitory')
            ->get();

        return view('beds.index', compact('beds', 'rooms'));
    }

    public function store(StoreBedRequest $request)
    {
        $data = $request->validated();

        // room_id est déjà validé : appartient à l'hostel + type dormitory
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