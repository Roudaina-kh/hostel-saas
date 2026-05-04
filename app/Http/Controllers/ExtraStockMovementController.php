<?php

namespace App\Http\Controllers;

use App\Models\Extra;
use App\Models\ExtraStockMovement;
use App\Http\Requests\StoreExtraStockMovementRequest;

class ExtraStockMovementController extends Controller
{
    public function index(Extra $extra)
    {
        abort_unless($extra->hostel_id === (int) session('hostel_id'), 403);

        $movements = $extra->stockMovements()
            ->with('creator')
            ->latest()
            ->get();

        $users = \App\Models\User::whereHas('hostels', fn($q) =>
            $q->where('hostels.id', session('hostel_id'))
        )->get();

        return view('extras.movements', compact('extra', 'movements', 'users'));
    }

    public function store(StoreExtraStockMovementRequest $request, Extra $extra)
    {
        abort_unless($extra->hostel_id === (int) session('hostel_id'), 403);

        $data = $request->validated();

        // On retire le mot de passe — il ne doit pas être persisté
        unset($data['password']);

        $data['hostel_id'] = session('hostel_id');
        $data['extra_id']  = $extra->id;

        // La mise à jour de stock_quantity est gérée automatiquement
        // par le hook `booted()` du model ExtraStockMovement.
        // NE PAS appeler increment/decrement ici pour éviter le double-update.
        ExtraStockMovement::create($data);

        return redirect()->route('extras.movements', $extra)
            ->with('success', 'Mouvement enregistré. Stock mis à jour.');
    }

    public function destroy(ExtraStockMovement $extraStockMovement)
    {
        abort_unless(
            $extraStockMovement->hostel_id === (int) session('hostel_id'),
            403
        );

        // L'annulation du stock est gérée automatiquement
        // par le hook `deleted()` du model ExtraStockMovement.
        $extraStockMovement->delete();

        return redirect()->back()
            ->with('success', 'Mouvement supprimé. Stock corrigé.');
    }
}