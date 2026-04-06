<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
{
    private function hostelId(): int
    {
        return (int) session('hostel_id');
    }

    public function index()
    {
        $hostelId = $this->hostelId();

        $managers = User::whereHas('hostels', function ($q) use ($hostelId) {
            $q->where('hostels.id', $hostelId);
        })->with(['hostels' => function ($q) use ($hostelId) {
            $q->where('hostels.id', $hostelId);
        }])->get()->map(function ($u) {
            $u->role      = $u->hostels->first()?->pivot->role;
            $u->is_active = $u->hostels->first()?->pivot->status === 'active';
            return $u;
        });

        return view('manager.index', compact('managers'));
    }

    public function create()
    {
        return view('manager.create');
    }
    public function store(Request $request)
{
    $hostelId = $this->hostelId();

    $data = $request->validate([
        'name'     => 'required|string|max:100',
        'email'    => 'required|email',
        'password' => 'nullable|min:8|confirmed',
        'phone'    => 'nullable|string|max:30',
        'role'     => 'required|in:manager,staff,financial',
    ]);

    // Vérifier si le user existe déjà
    $user = User::where('email', $data['email'])->first();

    if ($user) {
        // User existant — vérifier s'il est déjà dans ce hostel
        $alreadyInHostel = $user->hostels()
            ->where('hostels.id', $hostelId)
            ->exists();

        if ($alreadyInHostel) {
            return back()->withErrors([
                'email' => 'Ce membre fait déjà partie de ce hostel.',
            ]);
        }

        // Attacher au nouveau hostel avec le nouveau rôle
        $user->hostels()->attach($hostelId, [
            'role'   => $data['role'],
            'status' => 'active',
        ]);

    } else {
        // Nouveau user — le mot de passe est obligatoire
        if (empty($data['password'])) {
            return back()->withErrors([
                'password' => 'Le mot de passe est obligatoire pour un nouveau membre.',
            ])->withInput();
        }

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
            'status'   => 'active',
        ]);

        $user->hostels()->attach($hostelId, [
            'role'   => $data['role'],
            'status' => 'active',
        ]);
    }

    return redirect()->route('managers.index')
        ->with('success', 'Membre de l\'équipe ajouté avec succès.');
}

    

    public function edit(User $manager) 
    {
        $hostelId = $this->hostelId();

        // Vérifier que ce user appartient bien à ce hostel
        $pivot = $manager->hostels()
            ->where('hostels.id', $hostelId)
            ->first();

        abort_unless($pivot, 403);

        $manager->role      = $pivot->pivot->role;
        $manager->is_active = $pivot->pivot->status === 'active';

       return view('manager.edit', compact('manager'));
    }

    public function update(Request $request, User $manager)
    {
        $hostelId = $this->hostelId();

        $data = $request->validate([
            'name'   => 'required|string|max:100',
            'phone'  => 'nullable|string|max:30',
            'role'   => 'required|in:manager,staff,financial',
            'status' => 'required|in:active,inactive',
        ]);

        // Mettre à jour les infos du user
        $manager->update([
            'name'   => $data['name'],
            'phone'  => $data['phone'],
            'status' => $data['status'],
        ]);

        // Mettre à jour le pivot hostel_user
        DB::table('hostel_user')
            ->where('hostel_id', $hostelId)
            ->where('user_id', $manager->id)
            ->update([
                'role'       => $data['role'],
                'status'     => $data['status'],
                'updated_at' => now(),
            ]);

        return redirect()->route('managers.index')
            ->with('success', 'Membre mis à jour.');
    }

    public function destroy(User $manager)
    {
        $hostelId = $this->hostelId();

        // Vérifier appartenance
        abort_unless(
            $manager->hostels()->where('hostels.id', $hostelId)->exists(),
            403
        );

        // Supprimer seulement l'affectation au hostel
        DB::table('hostel_user')
            ->where('hostel_id', $hostelId)
            ->where('user_id', $manager->id)
            ->delete();

        // Si le user n'a plus aucun hostel, supprimer le compte
        if ($manager->hostels()->count() === 0) {
            $manager->delete();
        }

        return redirect()->route('managers.index')
            ->with('success', 'Membre supprimé.');
    }
}