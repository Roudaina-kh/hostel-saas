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

    // Déplacer la requête ici depuis la vue
    $users = \App\Models\User::whereHas('hostels', fn($q) => 
        $q->where('hostels.id', session('hostel_id'))
    )->get();

    return view('extras.movements', compact('extra', 'movements', 'users'));
}
    public function store(StoreExtraStockMovementRequest $request, Extra $extra)
    {
        abort_unless($extra->hostel_id === (int) session('hostel_id'), 403);

        $data              = $request->validated();
        $data['hostel_id'] = session('hostel_id');
        $data['extra_id']  = $extra->id;

        // Sécurité : cohérence hostel_id du mouvement et de l'extra
        abort_unless(
            (int) $data['hostel_id'] === $extra->hostel_id,
            403,
            'Incohérence hostel.'
        );

        $movement = ExtraStockMovement::create($data);

        /**
         * Règle métier : mise à jour du stock courant dans extras.stock_quantity
         * Le movement_type détermine si on ajoute ou soustrait.
         * Mouvements positifs : initial, purchase, adjustment_in, return
         * Mouvements négatifs : adjustment_out, damage, loss
         */
        if ($extra->stock_mode !== 'unlimited') {
            $signed = $movement->getSignedQuantity();
            $extra->increment('stock_quantity', $signed);
        }

        return redirect()->route('extras.movements', $extra)
            ->with('success', 'Mouvement enregistré. Stock mis à jour.');
    }

    public function destroy(ExtraStockMovement $extraStockMovement)
    {
        abort_unless(
            $extraStockMovement->hostel_id === (int) session('hostel_id'),
            403
        );

        $extra = $extraStockMovement->extra;

        /**
         * Sécurité : annulation du mouvement = inversion de l'impact sur le stock.
         * Si le mouvement avait augmenté le stock, on le diminue, et vice versa.
         */
        if ($extra->stock_mode !== 'unlimited') {
            $signed = $extraStockMovement->getSignedQuantity();
            $extra->increment('stock_quantity', -$signed);
        }

        $extraStockMovement->delete();

        return redirect()->back()
            ->with('success', 'Mouvement supprimé. Stock corrigé.');
    }
}