<?php

namespace App\Http\Controllers;

use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HostelController extends Controller
{


public function storeFirst(Request $request)
{
    $data = $this->validateHostel($request);
    $data['owner_id'] = Auth::id();

    $hostel = Hostel::create($data);

    $request->session()->put('hostel_id', $hostel->id);
    $request->session()->save();

    return redirect()->route('dashboard')
        ->with('success', 'Votre hostel a été créé avec succès !');
}

public function onboarding()
{
    // Redirige vers dashboard si hostel déjà en session
    if (session('hostel_id')) {
        return redirect()->route('dashboard');
    }

    // Redirige si l'owner a déjà des hostels
    if (Auth::user()->hostels()->count() > 0) {
        $hostel = Auth::user()->hostels()->first();
        session(['hostel_id' => $hostel->id]);
        return redirect()->route('dashboard');
    }

    return view('onboarding.create');
}

    // Liste des hostels (section Hostels dans le menu)
    public function index()
    {
        $hostels = Auth::user()->hostels()->latest()->get();
        return view('hostels.index', compact('hostels'));
    }

    // Formulaire création hostel supplémentaire
    public function create()
    {
        return view('hostels.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateHostel($request);
        $data['owner_id'] = Auth::id();

        $hostel = Hostel::create($data);

        return redirect()->route('hostels.index')
            ->with('success', 'Hostel créé avec succès.');
    }

    public function edit(Hostel $hostel)
    {
        $this->authorizeHostel($hostel);
        return view('hostels.edit', compact('hostel'));
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
    }

    $hostel->delete();

    return redirect()->route('hostels.index')
        ->with('success', 'Hostel supprimé.');
}

    // Switcher de hostel
    public function switchHostel(Hostel $hostel)
    {
        $this->authorizeHostel($hostel);
        session(['hostel_id' => $hostel->id]);

        return redirect()->route('dashboard')
            ->with('success', "Hostel basculé vers {$hostel->name}");
    }

    // --- Helpers privés ---

    private function validateHostel(Request $request): array
    {
        return $request->validate([
            'name'             => 'required|string|max:150',
            'address'          => 'nullable|string|max:255',
            'city'             => 'required|string|max:100',
            'country'          => 'required|string|max:100',
            'phone'            => 'nullable|string|max:30',
            'email'            => 'nullable|email|max:150',
            'default_currency' => 'required|string|max:10',
            'timezone'         => 'nullable|string|max:60',
        ]);
    }

    private function authorizeHostel(Hostel $hostel): void
    {
        abort_unless(Auth::user()->hostels()->where('id', $hostel->id)->exists(), 403);
    }
}