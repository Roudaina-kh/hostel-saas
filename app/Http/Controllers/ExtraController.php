<?php

namespace App\Http\Controllers;

use App\Models\Extra;
use App\Models\ExtraStockMovement;
use App\Http\Requests\StoreExtraRequest;
use App\Http\Requests\UpdateExtraRequest;

class ExtraController extends Controller
{
    public function index()
    {
        $extras = Extra::where('hostel_id', session('hostel_id'))
            ->latest()
            ->get();

        return view('extras.index', compact('extras'));
    }

    public function create()
    {
        return view('extras.create');
    }

    public function store(StoreExtraRequest $request)
{
    $data = $request->validated();
    $data['hostel_id'] = session('hostel_id'); // ← depuis session, jamais du formulaire

    Extra::create($data);

    return redirect()->route('extras.index')->with('success', 'Extra créé.');
}
    public function edit(Extra $extra)
    {
        $this->authorizeExtra($extra);
        return view('extras.edit', compact('extra'));
    }

    public function update(UpdateExtraRequest $request, Extra $extra)
    {
        $this->authorizeExtra($extra);
        $extra->update($request->validated());

        return redirect()->route('extras.index')
            ->with('success', 'Extra mis à jour.');
    }

    public function destroy(Extra $extra)
    {
        $this->authorizeExtra($extra);
        $extra->delete();

        return redirect()->route('extras.index')
            ->with('success', 'Extra supprimé.');
    }

    private function authorizeExtra(Extra $extra): void
    {
        abort_unless($extra->hostel_id === (int) session('hostel_id'), 403);
    }
}