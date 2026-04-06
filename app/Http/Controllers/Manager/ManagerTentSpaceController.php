<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TentSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerTentSpaceController extends Controller
{
    private function hostelId(): int
    {
        return session('staff_hostel_id') ?? abort(403, 'Aucun hostel sélectionné.');
    }



    public function index()
    {
        $tentSpaces = TentSpace::where('hostel_id', $this->hostelId())->latest()->get();
        return view('manager.tent-spaces.index', compact('tentSpaces'));
    }

    public function create()
    {
        return view('manager.tent-spaces.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateTent($request);
        $data['hostel_id'] = $this->hostelId();
        TentSpace::create($data);

        return redirect()->route('manager.tent-spaces.index')
            ->with('success', 'Espace tente créé avec succès.');
    }

    public function edit(TentSpace $tentSpace)
    {
        $this->authorizeTent($tentSpace);
        return view('manager.tent-spaces.edit', compact('tentSpace'));
    }

    public function update(Request $request, TentSpace $tentSpace)
    {
        $this->authorizeTent($tentSpace);
        $tentSpace->update($this->validateTent($request));

        return redirect()->route('manager.tent-spaces.index')
            ->with('success', 'Espace tente mis à jour.');
    }

    public function destroy(TentSpace $tentSpace)
    {
        $this->authorizeTent($tentSpace);
        $tentSpace->delete();

        return redirect()->route('manager.tent-spaces.index')
            ->with('success', 'Espace tente supprimé avec succès.');
    }

    private function validateTent(Request $request): array
    {
        return $request->validate([
            'name'        => 'required|string|max:150',
            'max_tents'   => 'required|integer|min:1',
            'status'      => 'required|in:active,maintenance,inactive',
            'description' => 'nullable|string',
        ]);
    }

    private function authorizeTent(TentSpace $tentSpace): void
    {
        abort_unless($tentSpace->hostel_id === $this->hostelId(), 403);
    }
}
