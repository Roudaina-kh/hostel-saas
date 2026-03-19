<?php

namespace App\Http\Controllers;

use App\Models\TentSpace;
use Illuminate\Http\Request;

class TentSpaceController extends Controller
{
    private function hostelId(): int
    {
        return session('hostel_id');
    }

    public function index()
    {
        $tentSpaces = TentSpace::where('hostel_id', $this->hostelId())->latest()->get();
        return view('tent-spaces.index', compact('tentSpaces'));
    }

    public function create()
    {
        return view('tent-spaces.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateTent($request);
        $data['hostel_id'] = $this->hostelId();
        TentSpace::create($data);

        return redirect()->route('tent-spaces.index')
            ->with('success', 'Espace tente créé avec succès.');
    }

    public function edit(TentSpace $tentSpace)
    {
        $this->authorizeTent($tentSpace);
        return view('tent-spaces.edit', compact('tentSpace'));
    }

    public function update(Request $request, TentSpace $tentSpace)
    {
        $this->authorizeTent($tentSpace);
        $tentSpace->update($this->validateTent($request));

        return redirect()->route('tent-spaces.index')
            ->with('success', 'Espace tente mis à jour.');
    }

public function destroy(TentSpace $tentSpace)
{
    $this->authorizeTent($tentSpace);
    $tentSpace->delete();

    return redirect()->route('tent-spaces.index')
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