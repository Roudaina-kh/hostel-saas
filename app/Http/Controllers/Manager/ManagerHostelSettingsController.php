<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerHostelSettingsController extends Controller
{
    private function hostelId(): int
    {
        return session('staff_hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');
    }

    public function edit()
    {
        $hostel = Hostel::findOrFail($this->hostelId());
        return view('manager.settings.edit', compact('hostel'));
    }

    public function update(Request $request)
    {
        $hostel = Hostel::findOrFail($this->hostelId());

        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'address'     => 'required|string',
            'phone'       => 'nullable|string|max:30',
            'email'       => 'nullable|email',
            'description' => 'nullable|string',
        ]);

        $hostel->update($data);

        return redirect()->route('manager.settings.edit')
            ->with('success', 'Paramètres du hostel mis à jour.');
    }
}
