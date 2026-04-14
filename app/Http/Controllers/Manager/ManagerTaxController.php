<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TaxSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerTaxController extends Controller
{
    private function hostelId(): int
    {
        return session('staff_hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');
    }



public function index()
{
    $hostelId = $this->hostelId();

    $taxes = \App\Models\Tax::where('hostel_id', $hostelId)
        ->latest()
        ->get();

    $taxSettings = \App\Models\Tax::where('hostel_id', $hostelId)->first();

    return view('manager.taxes.index', compact('taxes', 'taxSettings'));
}
    public function update(Request $request)
    {
        
        $data = $request->validate([
            'taxes_enabled'            => 'boolean',
            'vat_percentage'           => 'required|numeric|min:0|max:100',
            'city_tax_per_night'       => 'required|numeric|min:0',
            'per_person_tax_per_night' => 'required|numeric|min:0',
            'service_fee_percentage'   => 'required|numeric|min:0|max:100',
            'extras_taxable'           => 'boolean',
        ]);

        $data['taxes_enabled']  = $request->boolean('taxes_enabled');
        $data['extras_taxable'] = $request->boolean('extras_taxable');

        TaxSetting::updateOrCreate(
            ['hostel_id' => $this->hostelId()],
            $data
        );

        return redirect()->route('manager.taxes.index')
            ->with('with_success', 'Paramètres de taxes sauvegardés.');
    }
}
