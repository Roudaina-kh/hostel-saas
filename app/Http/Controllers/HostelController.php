<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelController extends Controller
{
    public function storeFirst(Request $request)
    {
        $data = $this->validateHostel($request);
        $data['owner_id'] = Auth::guard('owner')->id();

        $hostel = Hostel::create($data);

        $request->session()->put('hostel_id', $hostel->id);
        $request->session()->save();

        return redirect()->route('dashboard')
            ->with('success', 'Votre hostel a été créé avec succès !');
    }

 public function onboarding()
{
    if (session('hostel_id')) {
        return redirect()->route('dashboard');
    }
    if (Auth::guard('owner')->user()->hostels()->count() > 0) {
        $hostel = Auth::guard('owner')->user()->hostels()->first();
        session(['hostel_id' => $hostel->id]);
        return redirect()->route('dashboard');
    }

    // ✅ Charger les enfants (villes) aussi
    $regions = Region::with('children')->gouvernorats()->orderBy('name')->get();
    return view('onboarding.create', compact('regions'));
}

    // Liste des hostels du owner
    public function index()
    {
        $hostels     = Auth::guard('owner')->user()->hostels()->with('region')->latest()->get();
        $activeId    = session('hostel_id');
        return view('hostels.index', compact('hostels', 'activeId'));
    }

    // Formulaire création hostel supplémentaire
    public function create()
    {
        $regions = Region::with('children')->gouvernorats()->orderBy('name')->get();
        return view('hostels.create', compact('regions'));
    }

    public function store(Request $request)
    {
        $data = $this->validateHostel($request);
        $data['owner_id'] = Auth::guard('owner')->id();

        $hostel = Hostel::create($data);

        return redirect()->route('hostels.index')
            ->with('success', "Hostel « {$hostel->name} » créé avec succès.");
    }

    public function edit(Hostel $hostel)
    {
        $this->authorizeHostel($hostel);
        $regions = Region::with('children')->gouvernorats()->orderBy('name')->get();
        return view('hostels.edit', compact('hostel', 'regions'));
    }

    public function update(Request $request, Hostel $hostel)
    {
        $this->authorizeHostel($hostel);
        $hostel->update($this->validateHostel($request));
        return redirect()->route('hostels.index')
            ->with('success', 'Hostel mis à jour.');
    }

    public function destroy(Hostel $hostel)
    {
        $this->authorizeHostel($hostel);

        if (session('hostel_id') === $hostel->id) {
            session()->forget('hostel_id');
            // Sélectionner un autre hostel actif
            $other = Auth::guard('owner')->user()
                ->hostels()->where('id', '!=', $hostel->id)->first();
            if ($other) session(['hostel_id' => $other->id]);
        }

        $hostel->delete();

        return redirect()->route('hostels.index')
            ->with('success', 'Hostel supprimé.');
    }

    // ✅ Switcher de hostel
    public function switchHostel(Hostel $hostel)
    {
        $this->authorizeHostel($hostel);

        // Vérifier que le hostel est actif
        if (!($hostel->is_active ?? true)) {
            return back()->with('error', "Ce hostel est désactivé par l'administrateur.");
        }

        session(['hostel_id' => $hostel->id]);

        return redirect()->route('dashboard')
            ->with('success', "Basculé vers : {$hostel->name}");
    }

    // ─────────────────────────────────────────────────────
    // Helpers privés
    // ─────────────────────────────────────────────────────

private function validateHostel(Request $request): array
{
    return $request->validate([
        'name'             => 'required|string|max:150',
        'type'             => 'nullable|in:hostel,camping,mixed',
        'region_id'        => 'required|exists:regions,id',  // ✅ required, plus jamais NULL
        'address'          => 'nullable|string|max:255',
        'city'             => 'nullable|string|max:100',
        'country'          => 'nullable|string|max:100',
        'phone'            => 'nullable|string|max:30',
        'email'            => 'nullable|email|max:150',
        'description'      => 'nullable|string|max:2000',
        'latitude'         => 'nullable|numeric|between:-90,90',
        'longitude'        => 'nullable|numeric|between:-180,180',
        'default_currency' => 'nullable|string|max:10',
        'timezone'         => 'nullable|string|max:60',
    ], [
        'region_id.required' => 'Veuillez sélectionner une région / gouvernorat.',
        'region_id.exists'   => 'La région sélectionnée est invalide.',
    ]);
}

    private function authorizeHostel(Hostel $hostel): void
    {
        abort_unless(
            Auth::guard('owner')->user()->hostels()->where('id', $hostel->id)->exists(),
            403,
            'Accès non autorisé à ce hostel.'
        );
    }
}