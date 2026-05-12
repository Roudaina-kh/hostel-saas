<?php

namespace App\Http\Controllers\Staff;

use App\Enums\ExpenseCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use App\Models\Hostel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Gestion des dépenses côté MANAGER / STAFF / FINANCIAL (guard : auth:user)
 *
 * Tous les rôles user ont accès aux expenses (financial inclus,
 * car c'est leur cœur de métier).
 */
class StaffExpenseController extends Controller
{
    // ── Helpers ────────────────────────────────────────────────────────────

    private function currentHostel(): Hostel
    {
        $hostelId = session('staff_hostel_id');
        abort_if(!$hostelId, 403, 'Aucun hostel sélectionné.');

        $hostel = auth('user')->user()
            ->hostels()
            ->where('hostels.id', $hostelId)
            ->first();

        abort_if(!$hostel, 403, 'Accès non autorisé à ce hostel.');
        return $hostel;
    }

    private function currentRole(): string
    {
        $user     = auth('user')->user();
        $hostelId = session('staff_hostel_id');
        if (!$user || !$hostelId) return 'unknown';
        return $user->hostels()
            ->where('hostels.id', $hostelId)
            ->first()?->pivot->role ?? 'unknown';
    }

    private function creatorLabel(): string
    {
        $user = auth('user')->user();
        $role = match ($this->currentRole()) {
            'manager'   => 'Manager',
            'staff'     => 'Staff',
            'financial' => 'Financier',
            default     => 'Utilisateur',
        };
        return $user->name . ' (' . $role . ')';
    }

    private function getRoutePrefix(): string
    {
        $routeName = request()->route()?->getName() ?? '';
        return str_starts_with($routeName, 'manager.') ? 'manager' : 'staff';
    }

    // ── INDEX ──────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $hostel = $this->currentHostel();
        $prefix = $this->getRoutePrefix();

        $query = Expense::where('hostel_id', $hostel->id);

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
            'index'   => $prefix . '.expenses.index',
            'create'  => $prefix . '.expenses.create',
            'store'   => $prefix . '.expenses.store',
            'edit'    => $prefix . '.expenses.edit',
            'update'  => $prefix . '.expenses.update',
            'destroy' => $prefix . '.expenses.destroy',
            'pwd'     => $prefix . '.expenses.check-password',
        ];

        // Alias pour les vues partagées
        $activeHostel = $hostel;

        return view('expenses.index', compact(
            'hostel', 'activeHostel', 'expenses', 'stats', 'categories', 'routes'
        ));
    }

    // ── CREATE ─────────────────────────────────────────────────────────────

    public function create()
    {
        $hostel     = $this->currentHostel();
        $prefix     = $this->getRoutePrefix();
        $categories = ExpenseCategory::options();

        $routes = [
            'index' => $prefix . '.expenses.index',
            'store' => $prefix . '.expenses.store',
            'pwd'   => $prefix . '.expenses.check-password',
        ];

        $activeHostel = $hostel;

        return view('expenses.create', compact('hostel', 'activeHostel', 'categories', 'routes'));
    }

    // ── STORE ──────────────────────────────────────────────────────────────

    public function store(StoreExpenseRequest $request)
    {
        $hostel = $this->currentHostel();
        $user   = auth('user')->user();
        $prefix = $this->getRoutePrefix();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $data = $request->validated();
        unset($data['password']);

        Expense::create(array_merge($data, [
            'hostel_id'     => $hostel->id,
            'user_id'       => $user->id,
            'owner_id'      => null,
            'creator_label' => $this->creatorLabel(),
        ]));

        return redirect()->route($prefix . '.expenses.index')
            ->with('success', '✅ Dépense enregistrée avec succès.');
    }

    // ── EDIT ───────────────────────────────────────────────────────────────

    public function edit(int $id)
    {
        $hostel     = $this->currentHostel();
        $prefix     = $this->getRoutePrefix();
        $expense    = Expense::where('hostel_id', $hostel->id)->findOrFail($id);
        $categories = ExpenseCategory::options();

        $routes = [
            'index'  => $prefix . '.expenses.index',
            'update' => $prefix . '.expenses.update',
            'pwd'    => $prefix . '.expenses.check-password',
        ];

        $activeHostel = $hostel;

        return view('expenses.edit', compact('hostel', 'activeHostel', 'expense', 'categories', 'routes'));
    }

    // ── UPDATE ─────────────────────────────────────────────────────────────

    public function update(UpdateExpenseRequest $request, int $id)
    {
        $hostel  = $this->currentHostel();
        $user    = auth('user')->user();
        $prefix  = $this->getRoutePrefix();
        $expense = Expense::where('hostel_id', $hostel->id)->findOrFail($id);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withInput()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $data = $request->validated();
        unset($data['password']);

        $expense->update($data);

        return redirect()->route($prefix . '.expenses.index')
            ->with('success', '✅ Dépense mise à jour.');
    }

    // ── DESTROY ────────────────────────────────────────────────────────────

    public function destroy(Request $request, int $id)
    {
        $hostel  = $this->currentHostel();
        $user    = auth('user')->user();
        $prefix  = $this->getRoutePrefix();
        $expense = Expense::where('hostel_id', $hostel->id)->findOrFail($id);

        $request->validate(['password' => ['required', 'string']]);

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $expense->delete();

        return redirect()->route($prefix . '.expenses.index')
            ->with('success', '🗑️ Dépense supprimée.');
    }

    // ── AJAX : check-password ──────────────────────────────────────────────

    public function checkPassword(Request $request)
    {
        $request->validate(['password' => ['required', 'string']]);
        $success = Hash::check($request->password, auth('user')->user()->password);
        return response()->json(['success' => $success]);
    }
}