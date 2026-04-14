<?php

namespace App\Http\Controllers;

use App\Models\Extra;
use App\Models\Price;
use App\Models\Room;
use App\Models\Tax;
use App\Models\TentSpace;
use App\Http\Requests\StorePriceRequest;
use App\Http\Requests\UpdatePriceRequest;

class PriceController extends Controller
{
    public function index()
    {
        $hostelId = session('hostel_id');

        $prices = Price::where('hostel_id', $hostelId)
            ->with(['priceable', 'taxes'])
            ->latest()
            ->get();

        return view('prices.index', compact('prices'));
    }

    public function create()
    {
        $hostelId   = session('hostel_id');
        $rooms      = Room::where('hostel_id', $hostelId)->get();
        $tentSpaces = TentSpace::where('hostel_id', $hostelId)->get();
        $extras     = Extra::where('hostel_id', $hostelId)->get();
        $taxes      = Tax::where('hostel_id', $hostelId)->where('is_enabled', true)->get();

        return view('prices.create', compact('rooms', 'tentSpaces', 'extras', 'taxes'));
    }

    public function store(StorePriceRequest $request)
    {
        $hostelId = session('hostel_id');
        $data     = $request->validated();
        $data['hostel_id'] = $hostelId;

        // Sécurité : vérifier que le priceable appartient au hostel actif
        $this->verifyPriceableOwnership(
            $data['priceable_type'],
            $data['priceable_id'],
            $hostelId
        );

        $taxIds = $data['tax_ids'] ?? [];
        unset($data['tax_ids']);

        $price = Price::create($data);

        // Associer les taxes via price_tax
        if (! empty($taxIds)) {
            $price->taxes()->sync($taxIds);
        }

        return redirect()->route('prices.index')
            ->with('success', 'Tarif créé avec succès.');
    }

    public function edit(Price $price)
    {
        $this->authorizePrice($price);

        $hostelId   = session('hostel_id');
        $rooms      = Room::where('hostel_id', $hostelId)->get();
        $tentSpaces = TentSpace::where('hostel_id', $hostelId)->get();
        $extras     = Extra::where('hostel_id', $hostelId)->get();
        $taxes      = Tax::where('hostel_id', $hostelId)->where('is_enabled', true)->get();

        $price->load('taxes');

        return view('prices.edit', compact('price', 'rooms', 'tentSpaces', 'extras', 'taxes'));
    }

    public function update(UpdatePriceRequest $request, Price $price)
    {
        $this->authorizePrice($price);

        $data   = $request->validated();
        $taxIds = $data['tax_ids'] ?? [];
        unset($data['tax_ids']);

        $price->update($data);

        // Mettre à jour les taxes associées
        $price->taxes()->sync($taxIds);

        return redirect()->route('prices.index')
            ->with('success', 'Tarif mis à jour.');
    }

    public function destroy(Price $price)
    {
        $this->authorizePrice($price);
        $price->taxes()->detach();
        $price->delete();

        return redirect()->route('prices.index')
            ->with('success', 'Tarif supprimé.');
    }

    /**
     * Sécurité : vérifie que l'élément tarifé appartient au hostel actif.
     */
    private function verifyPriceableOwnership(string $type, int $id, int $hostelId): void
    {
        $belongs = match ($type) {
            'room'       => Room::where('id', $id)->where('hostel_id', $hostelId)->exists(),
            'tent_space' => TentSpace::where('id', $id)->where('hostel_id', $hostelId)->exists(),
            'extra'      => Extra::where('id', $id)->where('hostel_id', $hostelId)->exists(),
            default      => false,
        };

        abort_unless($belongs, 403, 'Cet élément n\'appartient pas à votre hostel.');
    }

    private function authorizePrice(Price $price): void
    {
        abort_unless($price->hostel_id === (int) session('hostel_id'), 403);
    }
}