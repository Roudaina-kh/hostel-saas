<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class FinancialDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('user')->user();

        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->first();

        if (!$hostel) {
            Auth::guard('user')->logout();
            return redirect()->route('login')
                ->with('error', 'Aucun hostel actif trouvé.');
        }

        $now   = Carbon::now();
        $mS    = $now->copy()->startOfMonth();
        $mE    = $now->copy()->endOfMonth();
        $pS    = $now->copy()->subMonth()->startOfMonth();
        $pE    = $now->copy()->subMonth()->endOfMonth();

        // Ce mois — revenus via reservation.start_date, dépenses via expense_date
        $monthlyRevenue  = $this->revenue($hostel->id, $mS, $mE);
        $monthlyExpenses = $this->expenses($hostel->id, $mS, $mE);
        $netProfit       = $monthlyRevenue - $monthlyExpenses;

        // Mois précédent (pour les badges d'évolution)
        $prevRevenue  = $this->revenue($hostel->id, $pS, $pE);
        $prevExpenses = $this->expenses($hostel->id, $pS, $pE);

        $totalBeds = 0;
        if (Schema::hasTable('rooms') && Schema::hasTable('beds')) {
            $totalBeds = $hostel->rooms()->withCount('beds')->get()->sum('beds_count');
        }

        // Graphique 6 mois (barres revenus / dépenses)
        $chartLabels = $chartRev = $chartExp = [];
        for ($i = 5; $i >= 0; $i--) {
            $m  = $now->copy()->subMonths($i);
            $mS2 = $m->copy()->startOfMonth();
            $mE2 = $m->copy()->endOfMonth();
            $chartLabels[] = $m->format('M Y');
            $chartRev[]    = round($this->revenue($hostel->id, $mS2, $mE2), 2);
            $chartExp[]    = round($this->expenses($hostel->id, $mS2, $mE2), 2);
        }

        // Derniers paiements (5)
        $recentPayments = Payment::whereHas('reservation', fn($q) => $q->where('hostel_id', $hostel->id))
            ->where('status', 'paid')
            ->with('reservation.mainGuest')
            ->latest()->take(5)->get();

        // Dernières dépenses (5)
        $recentExpenses = Expense::where('hostel_id', $hostel->id)
            ->orderByDesc('expense_date')->take(5)->get();

        return view('staff.financial.dashboard', compact(
            'user', 'hostel',
            'monthlyRevenue', 'monthlyExpenses', 'netProfit', 'totalBeds',
            'prevRevenue', 'prevExpenses',
            'chartLabels', 'chartRev', 'chartExp',
            'recentPayments', 'recentExpenses'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function revenue(int $hostelId, Carbon $from, Carbon $to): float
    {
        return (float) Payment::where('status', 'paid')
            ->whereHas('reservation', function ($q) use ($hostelId, $from, $to) {
                $q->where('hostel_id', $hostelId)
                  ->where('start_date', '>=', $from->toDateString())
                  ->where('start_date', '<=', $to->toDateString());
            })
            ->sum('amount_tnd');
    }

    private function expenses(int $hostelId, Carbon $from, Carbon $to): float
    {
        return (float) Expense::where('hostel_id', $hostelId)
            ->where('expense_date', '>=', $from->toDateString())
            ->where('expense_date', '<=', $to->toDateString())
            ->sum('amount');
    }
}
