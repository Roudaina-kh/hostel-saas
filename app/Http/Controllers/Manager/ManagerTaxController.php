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

    private function checkPermission(): void
    {
        $user = Auth::guard('staff')->user();
        abort_unless($user->hasPermission('can_manage_taxes', $this->hostelId()), 403, 'Permission refusée.');
    }

public function index()
{
    $user     = Auth::guard('staff')->user();
    $hostelId = $this->hostelId();
    $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();

    $tax = TaxSetting::firstOrCreate(
        ['hostel_id' => $hostelId],
        [
            'taxes_enabled'            => false,
            'vat_percentage'           => 0,
            'city_tax_per_night'       => 0,
            'per_person_tax_per_night' => 0,
            'service_fee_percentage'   => 0,
            'extras_taxable'           => false,
        ]
    );

    return view('manager.taxes.index', compact('tax', 'hostel', 'user'));
}
    public function update(Request $request)
    {
        $this->checkPermission();
        
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
