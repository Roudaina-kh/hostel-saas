<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;

class SuperAdminManagerController extends Controller
{
    // ── Liste tous les managers de la plateforme ─────────────────
    public function index()
    {
        $managers = User::whereHas('hostels', fn($q) => $q->wherePivot('role', 'manager'))
            ->with(['hostels' => fn($q) => $q->wherePivot('role', 'manager')])
            ->latest()
            ->paginate(20);

        return view('super-admin.managers.index', compact('managers'));
    }

    // ── Bloquer / Débloquer un manager ──────────────────────────
    // 🔒 Agit au niveau plateforme — le manager ne peut plus se connecter
    public function toggle(User $user)
    {
        // Vérification : s'assurer que c'est bien un manager
        $isManager = $user->hostels()
            ->wherePivot('role', 'manager')
            ->exists();

        abort_unless($isManager, 403, 'Cet utilisateur n\'est pas un manager.');

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'débloqué' : 'bloqué';
        return back()->with('success', "Manager {$user->name} {$status} sur toute la plateforme.");
    }
}