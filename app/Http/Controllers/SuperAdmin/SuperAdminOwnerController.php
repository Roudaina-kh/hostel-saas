<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Owner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminOwnerController extends Controller
{
    public function index()
    {
        $owners = Owner::withCount('hostels')->latest()->get();
        return view('super-admin.owners.index', compact('owners'));
    }

    public function show(Owner $owner)
    {
        $owner->load('hostels');
        return view('super-admin.owners.show', compact('owner'));
    }

    public function destroy(Owner $owner)
    {
        $owner->delete();
        return redirect()->route('super-admin.owners.index')
            ->with('success', 'Compte propriétaire supprimé.');
    }
}