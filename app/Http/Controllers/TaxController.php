<?php

namespace App\Http\Controllers;

use App\Models\TaxSetting;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    private function hostelId(): int
    {
        return session('hostel_id');
    }

    public function index()
    {
        $tax = TaxSetting::firstOrCreate(
            ['hostel_id' => $this->hostelId()],
            [
                'taxes_enabled'            => false,
                'vat_percentage'           => 0,
                'city_tax_per_night'       => 0,
                'per_person_tax_per_night' => 0,
                'service_fee_percentage'   => 0,
                'extras_taxable'           => false,
            ]
        );

        return view('taxes.index', compact('tax'));
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

        return redirect()->route('taxes.index')
            ->with('success', 'Paramètres de taxes sauvegardés.');
    }
}