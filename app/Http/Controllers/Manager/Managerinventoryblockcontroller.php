<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\InventoryBlock;
use App\Models\Room;
use App\Models\Bed;
use App\Models\TentSpace;
use Illuminate\Http\Request;

class ManagerInventoryBlockController extends Controller
{
    private function hostelId(): int
    {
        return (int) session('staff_hostel_id');
    }

    /**
     * Retourne toutes les entités bloquables du hostel,
     * groupées par type, pour alimenter le formulaire.
     */
    private function blockables(): array
    {
        $hostelId = $this->hostelId();

        $rooms = Room::where('hostel_id', $hostelId)
            ->where('is_enabled', true)
            ->orderBy('name')
            ->get();

        // Beds récupérés via leurs rooms
        $beds = Bed::whereHas('room', fn($q) => $q->where('hostel_id', $hostelId))
            ->where('is_enabled', true)
            ->with('room')
            ->orderBy('name')
            ->get();

        $tentSpaces = TentSpace::where('hostel_id', $hostelId)
            ->where('is_enabled', true)
            ->orderBy('name')
            ->get();

        return compact('rooms', 'beds', 'tentSpaces');
    }

    public function index()
    {
        $hostelId = $this->hostelId();

        $blocks = InventoryBlock::where('hostel_id', $hostelId)
            ->with('blockable')
            ->orderByDesc('start_date')
            ->get();

        return view('manager.inventory-blocks.index', compact('blocks'));
    }

    public function create()
    {
        return view('manager.inventory-blocks.create', $this->blockables());
    }

    public function store(Request $request)
    {
        $hostelId = $this->hostelId();

        $data = $request->validate([
            'blockable_type' => ['required', 'in:room,bed,tent_space'],
            'blockable_id'   => ['required', 'integer'],
            'block_type'     => ['required', 'in:maintenance,manual_block'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'reason'         => ['nullable', 'string', 'max:100'],
            'note'           => ['nullable', 'string', 'max:1000'],
        ]);

        // Vérifier que l'élément ciblé appartient bien à ce hostel
        $this->authorizeBlockable($data['blockable_type'], $data['blockable_id'], $hostelId);

        $data['hostel_id'] = $hostelId;

        InventoryBlock::create($data);

        return redirect()->route('manager.inventory-blocks.index')
            ->with('success', 'Indisponibilité enregistrée.');
    }

    public function edit(InventoryBlock $inventoryBlock)
    {
        abort_unless($inventoryBlock->hostel_id === $this->hostelId(), 403);

        $data = $this->blockables();
        $data['block'] = $inventoryBlock;

        return view('manager.inventory-blocks.edit', $data);
    }

    public function update(Request $request, InventoryBlock $inventoryBlock)
    {
        abort_unless($inventoryBlock->hostel_id === $this->hostelId(), 403);

        $data = $request->validate([
            'blockable_type' => ['required', 'in:room,bed,tent_space'],
            'blockable_id'   => ['required', 'integer'],
            'block_type'     => ['required', 'in:maintenance,manual_block'],
            'start_date'     => ['required', 'date'],
            'end_date'       => ['nullable', 'date', 'after_or_equal:start_date'],
            'reason'         => ['nullable', 'string', 'max:100'],
            'note'           => ['nullable', 'string', 'max:1000'],
        ]);

        $this->authorizeBlockable($data['blockable_type'], $data['blockable_id'], $this->hostelId());

        $inventoryBlock->update($data);

        return redirect()->route('manager.inventory-blocks.index')
            ->with('success', 'Indisponibilité mise à jour.');
    }

    public function destroy(InventoryBlock $inventoryBlock)
    {
        abort_unless($inventoryBlock->hostel_id === $this->hostelId(), 403);

        $inventoryBlock->delete();

        return redirect()->route('manager.inventory-blocks.index')
            ->with('success', 'Indisponibilité supprimée.');
    }

    /**
     * Vérifie que l'élément bloquable appartient bien au hostel courant.
     */
    private function authorizeBlockable(string $type, int $id, int $hostelId): void
    {
        $exists = match ($type) {
            'room'       => Room::where('id', $id)->where('hostel_id', $hostelId)->exists(),
            'bed'        => Bed::where('id', $id)
                               ->whereHas('room', fn($q) => $q->where('hostel_id', $hostelId))
                               ->exists(),
            'tent_space' => TentSpace::where('id', $id)->where('hostel_id', $hostelId)->exists(),
            default      => false,
        };

        abort_unless($exists, 403, 'Élément introuvable dans ce hostel.');
    }
}