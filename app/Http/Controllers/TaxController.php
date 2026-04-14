<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Http\Requests\StoreTaxRequest;
use App\Http\Requests\UpdateTaxRequest;

class TaxController extends Controller
{
    public function index()
    {
        $taxes = Tax::where('hostel_id', session('hostel_id'))
            ->latest()
            ->get();

        return view('taxes.index', compact('taxes'));
    }

    public function create()
    {
        return view('taxes.create');
    }

    public function store(StoreTaxRequest $request)
    {
        $data = $request->validated();
        $data['hostel_id'] = session('hostel_id');

        Tax::create($data);

        return redirect()->route('taxes.index')
            ->with('success', 'Taxe créée avec succès.');
    }

    public function edit(Tax $tax)
    {
        $this->authorizeTax($tax);
        return view('taxes.edit', compact('tax'));
    }

    public function update(UpdateTaxRequest $request, Tax $tax)
    {
        $this->authorizeTax($tax);
        $tax->update($request->validated());

        return redirect()->route('taxes.index')
            ->with('success', 'Taxe mise à jour.');
    }

    public function toggleEnabled(Tax $tax)
    {
        $this->authorizeTax($tax);
        $tax->update(['is_enabled' => ! $tax->is_enabled]);

        return response()->json([
            'success'    => true,
            'is_enabled' => $tax->is_enabled,
        ]);
    }

    public function destroy(Tax $tax)
    {
        $this->authorizeTax($tax);
        $tax->delete();

        return redirect()->route('taxes.index')
            ->with('success', 'Taxe supprimée.');
    }

    private function authorizeTax(Tax $tax): void
    {
        abort_unless($tax->hostel_id === (int) session('hostel_id'), 403);
    }
}