<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SuperAdminOwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::withCount('hostels')->latest()->paginate(20);
        return view('super-admin.owners.index', compact('owners'));
    }

    public function show(Owner $owner)
    {
        $owner->load('hostels');
        return view('super-admin.owners.show', compact('owner'));
    }

    public function create()
    {
        return view('super-admin.owners.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'email', 'unique:owners,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'email.unique'       => 'Cet email est déjà utilisé.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $owner = Owner::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'password'  => Hash::make($data['password']),
            'is_active' => true,
        ]);

        return redirect()->route('super-admin.owners.index')
            ->with('success', "Compte propriétaire créé pour {$owner->name}.");
    }

    public function toggle(Owner $owner)
    {
        $newStatus = !$owner->is_active;
        $owner->update(['is_active' => $newStatus]);
        $status = $newStatus ? 'activé' : 'désactivé';
        return back()->with('success', "Compte de {$owner->name} {$status}.");
    }

    public function destroy(Owner $owner)
    {
        $name = $owner->name;
        $owner->delete();
        return redirect()->route('super-admin.owners.index')
            ->with('success', "Compte de {$name} supprimé.");
    }
}