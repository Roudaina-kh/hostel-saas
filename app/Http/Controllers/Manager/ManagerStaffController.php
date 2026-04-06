<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManagerStaffController extends Controller
{
    private function hostelId(): int
    {
        return session('staff_hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');
    }



    public function index()
    {
        $hostel = \App\Models\Hostel::findOrFail($this->hostelId());
        $staff = $hostel->users()
            ->wherePivotIn('role', ['staff', 'financial'])
            ->latest()
            ->get();

        return view('manager.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('manager.staff.create');
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:staff,financial',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'status'   => 'active',
        ]);

        $user->hostels()->attach($this->hostelId(), [
            'role'   => $data['role'],
            'status' => 'active',
        ]);

        return redirect()->route('manager.staff.index')
            ->with('success', 'Membre de l\'équipe créé avec succès.');
    }

    public function edit(User $staff)
    {
        
        $pivot = $staff->hostels()->where('hostels.id', $this->hostelId())->first()?->pivot;
        abort_unless($pivot, 403);
        
        // Simuler is_active pour la vue
        $staff->is_active = $pivot->status === 'active';
        $staff->role = $pivot->role;

        return view('manager.staff.edit', compact('staff'));
    }

    public function update(Request $request, User $staff)
    {
        
        $pivot = $staff->hostels()->where('hostels.id', $this->hostelId())->first()?->pivot;
        abort_unless($pivot, 403);

        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $staff->id,
            'role'      => 'required|in:staff,financial',
        ]);

        $status = $request->has('is_active') ? 'active' : 'inactive';

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $staff->update(['password' => Hash::make($request->password)]);
        }

        $staff->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);

        $staff->hostels()->updateExistingPivot($this->hostelId(), [
            'role'   => $data['role'],
            'status' => $status,
        ]);

        return redirect()->route('manager.staff.index')
            ->with('success', 'Membre de l\'équipe mis à jour.');
    }

    public function destroy(User $staff)
    {
        
        $exists = $staff->hostels()->where('hostels.id', $this->hostelId())->exists();
        abort_unless($exists, 403);
        
        // On détache du hostel au lieu de supprimer le user (car il peut être dans d'autres hostels)
        $staff->hostels()->detach($this->hostelId());

        return redirect()->route('manager.staff.index')
            ->with('success', 'Membre de l\'équipe retiré du hostel.');
    }
}
