<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\InventoryBlock;
use App\Models\Room;
use App\Models\TentSpace;
use App\Http\Requests\StoreInventoryBlockRequest;
use App\Http\Requests\UpdateInventoryBlockRequest;

class InventoryBlockController extends Controller
{
    public function index()
    {
        $hostelId = session('hostel_id');

        // Chargement des blocs avec leur élément bloqué (room, bed ou tent_space)
        $blocks = InventoryBlock::where('hostel_id', $hostelId)
            ->with('blockable')
            ->latest()
            ->get();

        // Éléments disponibles pour créer un bloc
        $rooms      = Room::where('hostel_id', $hostelId)->get();
        $beds       = Bed::whereHas('room', fn($q) => $q->where('hostel_id', $hostelId))->with('room')->get();
        $tentSpaces = TentSpace::where('hostel_id', $hostelId)->get();

        return view('inventory-blocks.index', compact('blocks', 'rooms', 'beds', 'tentSpaces'));
    }

    public function store(StoreInventoryBlockRequest $request)
    {
        $data = $request->validated();
        $data['hostel_id'] = session('hostel_id');

        // Sécurité : vérifier la cohérence hostel / élément ciblé
        $this->verifyBlockableOwnership(
            $data['blockable_type'],
            $data['blockable_id'],
            $data['hostel_id']
        );

        InventoryBlock::create($data);

        return redirect()->route('inventory-blocks.index')
            ->with('success', 'Bloc d\'indisponibilité créé.');
    }

    public function update(UpdateInventoryBlockRequest $request, InventoryBlock $inventoryBlock)
    {
        $this->authorizeBlock($inventoryBlock);
        $inventoryBlock->update($request->validated());

        return redirect()->route('inventory-blocks.index')
            ->with('success', 'Bloc mis à jour.');
    }

    public function destroy(InventoryBlock $inventoryBlock)
    {
        $this->authorizeBlock($inventoryBlock);
        $inventoryBlock->delete();

        return redirect()->route('inventory-blocks.index')
            ->with('success', 'Bloc supprimé.');
    }

    /**
     * Sécurité : vérifie que l'élément bloqué appartient au hostel actif.
     * Empêche qu'un utilisateur bloque un élément d'un autre hostel.
     */
    private function verifyBlockableOwnership(string $type, int $id, int $hostelId): void
    {
        $belongs = match ($type) {
            'room'       => Room::where('id', $id)->where('hostel_id', $hostelId)->exists(),
            'bed'        => Bed::whereHas('room', fn($q) => $q->where('hostel_id', $hostelId))->where('id', $id)->exists(),
            'tent_space' => TentSpace::where('id', $id)->where('hostel_id', $hostelId)->exists(),
            default      => false,
        };

        abort_unless($belongs, 403, 'Cet élément n\'appartient pas à votre hostel.');
    }

    private function authorizeBlock(InventoryBlock $block): void
    {
        abort_unless($block->hostel_id === (int) session('hostel_id'), 403);
    }
}