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

        // Récupère les lits via les rooms du hostel
        $beds = Bed::whereHas('room', function ($q) use ($hostelId) {
            $q->where('hostel_id', $hostelId);
        })->with('room')->latest()->get();

        // Seulement les dortoirs pour le formulaire d'ajout
        $rooms = Room::where('hostel_id', $hostelId)
            ->where('type', 'dormitory')
            ->get();

        return view('beds.index', compact('beds', 'rooms'));
    }

    public function store(StoreBedRequest $request)
    {
        $hostelId = session('hostel_id');
        $data     = $request->validated();

        // Sécurité : vérifier que la room appartient au hostel actif
        $room = Room::where('id', $data['room_id'])
            ->where('hostel_id', $hostelId)
            ->where('type', 'dormitory') // Sécurité : seulement les dortoirs
            ->firstOrFail();

        Bed::create([
            'room_id'    => $room->id,
            'name'       => $data['name'],
            'is_enabled' => $data['is_enabled'] ?? true,
        ]);

        return redirect()->route('beds.index')
            ->with('success', 'Lit ajouté avec succès.');
    }

    public function update(UpdateBedRequest $request, Bed $bed)
    {
        $this->authorizeBed($bed);
        $bed->update($request->validated());

        return redirect()->route('beds.index')
            ->with('success', 'Lit mis à jour.');
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

        return redirect()->route('beds.index')
            ->with('success', 'Lit supprimé.');
    }

    // Sécurité : vérifier que le lit appartient au hostel actif via sa room
    private function authorizeBed(Bed $bed): void
    {
        abort_unless(
            $bed->room->hostel_id === (int) session('hostel_id'),
            403
        );
    }
}