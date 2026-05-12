<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Gestion des dépenses côté OWNER (guard : auth:owner)
 *
 * Defense in depth :
 *   1. Session hostel_id écrite par le hostel switcher
 *   2. Revérification ownership (->hostels()->where('hostels.id', $id))
 *   3. Toute query Expense scopée par hostel_id
 */
class ExpenseController extends Controller
{
    // ── Helpers ────────────────────────────────────────────────────────────

    private function currentHostel(): Hostel
    {
        $hostelId = session('hostel_id');
        abort_if(!$hostelId, 403, 'Aucun hostel sélectionné.');

        $hostel = auth('owner')->user()
            ->hostels()
            ->where('hostels.id', $hostelId)
            ->first();

        abort_if(!$hostel, 403, 'Accès non autorisé à ce hostel.');
        return $hostel;
    }

    private function creatorLabel(): string
    {
        $owner = auth('owner')->user();
        return $owner->name . ' (Propriétaire)';
    }

    // ── INDEX ──────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $hostel = $this->currentHostel();

        $query = Expense::where('hostel_id', $hostel->id);

        // Filtres
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('from')) {
            $query->whereDate('expense_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('expense_date', '<=', $request->to);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('label', 'like', "%{$s}%")
                  ->orWhere('payer_name', 'like', "%{$s}%")
                  ->orWhere('note', 'like', "%{$s}%");
            });
        }

        $expenses = $query->orderByDesc('expense_date')->orderByDesc('id')->paginate(20)->withQueryString();

        // Stats globales (toutes les expenses, pas seulement la page courante)
        $statsQuery = Expense::where('hostel_id', $hostel->id);
        $stats = [
            'total_count'  => (clone $statsQuery)->count(),
            'total_amount' => (clone $statsQuery)->where('currency', 'TND')->sum('amount'),
            'this_month'   => (clone $statsQuery)
                ->whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->where('currency', 'TND')->sum('amount'),
            'by_category'  => (clone $statsQuery)
                ->where('currency', 'TND')
                ->groupBy('category')
                ->selectRaw('category, SUM(amount) as total')
                ->pluck('total', 'category')
                ->toArray(),
        ];

        $categories = ExpenseCategory::options();

        $routes = [
            'index'   => 'expenses.index',
            'create'  => 'expenses.create',
            'store'   => 'expenses.store',
            'edit'    => 'expenses.edit',
            'update'  => 'expenses.update',
            'destroy' => 'expenses.destroy',
            'pwd'     => 'expenses.check-password',
        ];

        return view('expenses.index', compact('hostel', 'expenses', 'stats', 'categories', 'routes'));
    }

    // ── CREATE ─────────────────────────────────────────────────────────────

    public function create()
    {
        $hostel     = $this->currentHostel();
        $categories = ExpenseCategory::options();

        $routes = [
            'index'  => 'expenses.index',
            'store'  => 'expenses.store',
            'pwd'    => 'expenses.check-password',
        ];

        return view('expenses.create', compact('hostel', 'categories', 'routes'));
    }

    // ── STORE ──────────────────────────────────────────────────────────────

    public function store(StoreExpenseRequest $request)
    {
        $hostel = $this->currentHostel();
        $owner  = auth('owner')->user();

        // Defense in depth : revérifier le password côté serveur même si JS l'a déjà fait
        if (!Hash::check($request->password, $owner->password)) {
            return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $data = $request->validated();
        unset($data['password']);

        Expense::create(array_merge($data, [
            'hostel_id'     => $hostel->id,
            'owner_id'      => $owner->id,
            'user_id'       => null,
            'creator_label' => $this->creatorLabel(),
        ]));

        return redirect()->route('expenses.index')
            ->with('success', '✅ Dépense enregistrée avec succès.');
    }

    // ── EDIT ───────────────────────────────────────────────────────────────

    public function edit(int $id)
    {
        $hostel  = $this->currentHostel();
        $expense = Expense::where('hostel_id', $hostel->id)->findOrFail($id);
        $categories = ExpenseCategory::options();

        $routes = [
            'index'  => 'expenses.index',
            'update' => 'expenses.update',
            'pwd'    => 'expenses.check-password',
        ];

        return view('expenses.edit', compact('hostel', 'expense', 'categories', 'routes'));
    }

    // ── UPDATE ─────────────────────────────────────────────────────────────

    public function update(UpdateExpenseRequest $request, int $id)
    {
        $hostel  = $this->currentHostel();
        $owner   = auth('owner')->user();
        $expense = Expense::where('hostel_id', $hostel->id)->findOrFail($id);

        if (!Hash::check($request->password, $owner->password)) {
            return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $data = $request->validated();
        unset($data['password']);

        $expense->update($data);

        return redirect()->route('expenses.index')
            ->with('success', '✅ Dépense mise à jour.');
    }

    // ── DESTROY ────────────────────────────────────────────────────────────

    public function destroy(Request $request, int $id)
    {
        $hostel  = $this->currentHostel();
        $owner   = auth('owner')->user();
        $expense = Expense::where('hostel_id', $hostel->id)->findOrFail($id);

        $request->validate(['password' => ['required', 'string']]);

        if (!Hash::check($request->password, $owner->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', '🗑️ Dépense supprimée.');
    }

    // ── AJAX : check-password ──────────────────────────────────────────────

    public function checkPassword(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);
        $success = Hash::check($request->password, auth('owner')->user()->password);
        return response()->json(['success' => $success]);
    }
}