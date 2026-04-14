<?php

namespace App\Http\Controllers;

use App\Models\TentSpace;
use App\Http\Requests\StoreTentSpaceRequest;
use App\Http\Requests\UpdateTentSpaceRequest;

class TentSpaceController extends Controller
{
    public function index()
    {
        $tentSpaces = TentSpace::where('hostel_id', session('hostel_id'))
            ->latest()->get();
        return view('tent-spaces.index', compact('tentSpaces'));
    }

    public function create()
    {
        return view('tent-spaces.create');
    }

    public function store(StoreTentSpaceRequest $request)
    {
        $data = $request->validated();
        $data['hostel_id'] = session('hostel_id');
        TentSpace::create($data);
        return redirect()->route('tent-spaces.index')
            ->with('success', 'Espace tente créé.');
    }

    public function edit(TentSpace $tentSpace)
    {
        $this->authorizeTent($tentSpace);
        return view('tent-spaces.edit', compact('tentSpace'));
    }

    public function update(UpdateTentSpaceRequest $request, TentSpace $tentSpace)
    {
        $this->authorizeTent($tentSpace);
        $tentSpace->update($request->validated());
        return redirect()->route('tent-spaces.index')
            ->with('success', 'Espace tente mis à jour.');
    }

    // ← Nouveau : toggle actif/inactif
public function toggle(TentSpace $tentSpace)
{
    $this->authorizeTent($tentSpace);
    $tentSpace->update(['is_enabled' => ! $tentSpace->is_enabled]);
    return response()->json(['success' => true, 'is_enabled' => $tentSpace->is_enabled]);
}

    public function destroy(TentSpace $tentSpace)
    {
        $this->authorizeTent($tentSpace);
        $tentSpace->delete();
        return redirect()->route('tent-spaces.index')
            ->with('success', 'Espace tente supprimé.');
    }

    private function authorizeTent(TentSpace $tentSpace): void
    {
        abort_unless($tentSpace->hostel_id === (int) session('hostel_id'), 403);
    }
}