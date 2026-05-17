<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
use App\Models\Expense;
use App\Models\Hostel;
use App\Models\Payment;
use App\Models\Reservation;
use App\Services\Analytics\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/**
 * Module Analytics — Dashboard BI multi-perspectives
 *
 * 3 onglets dans une page unique :
 *  - Acquisition : source d'origine, nationalité, funnel contact_requests
 *  - Occupation  : taux d'occupation, lead time, performance par type
 *  - Finance     : revenue, expenses, profit (logique historique conservée)
 *
 * Pattern PFE : Dashboard analytics multi-perspectives + Service Layer
 */
class AnalyticsController extends Controller
{
    public function __construct(
        private AnalyticsService $analyticsService,
    ) {}

    public function index(Request $request)
    {
        $hostelId = (int) session('hostel_id');
        $hostel   = Hostel::findOrFail($hostelId);

        // Onglet actif au chargement (utile pour partage de lien direct)
        $activeTab = $request->get('tab', 'acquisition');
        $activeTab = in_array($activeTab, ['acquisition', 'occupancy', 'finance'], true)
            ? $activeTab
            : 'acquisition';

        // Data des 3 onglets — toutes chargées en une fois (toggle = pur JS, pas de reload)
        $finance     = $this->compute($hostelId);                       // [existing] Finance
        $acquisition = $this->analyticsService->acquisitionData($hostelId); // [new] Acquisition
        $occupancy   = $this->analyticsService->occupancyData($hostelId);   // [new] Occupation

return view('analytics.index', array_merge(
    ['hostel' => $hostel, 'activeTab' => $activeTab],
    $finance,                          // ← spread finance keys at top level
    ['finance'     => $finance],
    ['acquisition' => $acquisition],
    ['occupancy'   => $occupancy],
    ['data'        => $acquisition],
));
    }

    /**
     * Endpoint JSON — toujours actif, renvoie les data Finance (rétro-compatibilité).
     */
    public function data()
    {
        return response()->json($this->compute((int) session('hostel_id')));
    }

    // ═════════════════════════════════════════════════════════════════════════
    // FINANCE — logique historique conservée à l'identique
    // ═════════════════════════════════════════════════════════════════════════

    private function compute(int $hostelId): array
    {
        $now    = Carbon::now();
        $mStart = $now->copy()->startOfMonth();
        $pStart = $now->copy()->subMonth()->startOfMonth();
        $pEnd   = $now->copy()->subMonth()->endOfMonth();

        // All-time
        $totalRevenue  = $this->revenue($hostelId);
        $totalExpenses = $this->expenses($hostelId);
        $netProfit     = $totalRevenue - $totalExpenses;
        $marginPct     = $totalRevenue > 0 ? round($netProfit / $totalRevenue * 100, 1) : 0.0;
        $expenseRatio  = $totalRevenue > 0 ? round($totalExpenses / $totalRevenue * 100, 1) : 0.0;

        // This month
        $mRevenue  = $this->revenue($hostelId, $mStart, $now);
        $mExpenses = $this->expenses($hostelId, $mStart, $now);
        $mProfit   = $mRevenue - $mExpenses;

        // Last month
        $pRevenue  = $this->revenue($hostelId, $pStart, $pEnd);
        $pExpenses = $this->expenses($hostelId, $pStart, $pEnd);
        $pProfit   = $pRevenue - $pExpenses;

        // 12-month trend chart
        $chartLabels = $chartRev = $chartExp = $chartProfit = [];
        for ($i = 11; $i >= 0; $i--) {
            $m   = $now->copy()->subMonths($i);
            $mS  = $m->copy()->startOfMonth();
            $mE  = $m->copy()->endOfMonth();
            $rev = $this->revenue($hostelId, $mS, $mE);
            $exp = $this->expenses($hostelId, $mS, $mE);
            $chartLabels[] = $m->format('M Y');
            $chartRev[]    = round($rev, 2);
            $chartExp[]    = round($exp, 2);
            $chartProfit[] = round($rev - $exp, 2);
        }

        // Expense categories
        $catRows   = Expense::where('hostel_id', $hostelId)
            ->selectRaw('category, SUM(amount) as total')
            ->groupBy('category')->orderByDesc('total')->get();
        $catLabels = $catValues = [];
        foreach ($catRows as $row) {
            $enum        = ExpenseCategory::tryFrom($row->category);
            $catLabels[] = $enum ? $enum->emoji() . ' ' . $enum->label() : $row->category;
            $catValues[] = (float) $row->total;
        }

        // Recent payments
        $recentPayments = Payment::whereHas('reservation', fn($q) => $q->where('hostel_id', $hostelId))
            ->where('status', 'paid')
            ->with(['reservation.mainGuest'])
            ->latest()->take(8)->get()
            ->map(fn($p) => [
                'id'     => $p->id,
                'amount' => (float) $p->amount_tnd,
                'method' => $p->payment_method,
                'date'   => $p->created_at?->format('d/m/Y') ?? '—',
                'guest'  => trim(
                    ($p->reservation?->mainGuest?->first_name ?? '') . ' ' .
                    ($p->reservation?->mainGuest?->last_name  ?? '')
                ) ?: '—',
            ]);

        // Reservations
        $totalRes = Reservation::where('hostel_id', $hostelId)
            ->whereNotIn('status', ['cancelled'])->count();
        $mRes     = Reservation::where('hostel_id', $hostelId)
            ->whereNotIn('status', ['cancelled'])
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)->count();

        return compact(
            'totalRevenue', 'totalExpenses', 'netProfit', 'marginPct', 'expenseRatio',
            'mRevenue', 'mExpenses', 'mProfit',
            'pRevenue', 'pExpenses', 'pProfit',
            'chartLabels', 'chartRev', 'chartExp', 'chartProfit',
            'catLabels', 'catValues',
            'recentPayments',
            'totalRes', 'mRes'
        );
    }

    private function revenue(int $hostelId, ?Carbon $from = null, ?Carbon $to = null): float
    {
        // On utilise reservation.start_date (date du séjour) et non payment.created_at
        // pour attribuer le revenu au bon mois sur le graphique, indépendamment
        // du moment où le paiement a été enregistré en base (ex: seed en masse).
        return (float) Payment::where('status', 'paid')
            ->whereHas('reservation', function ($q) use ($hostelId, $from, $to) {
                $q->where('hostel_id', $hostelId);
                if ($from) $q->where('start_date', '>=', $from->toDateString());
                if ($to)   $q->where('start_date', '<=', $to->toDateString());
            })
            ->sum('amount_tnd');
    }

    private function expenses(int $hostelId, ?Carbon $from = null, ?Carbon $to = null): float
    {
        $q = Expense::where('hostel_id', $hostelId);
        if ($from) $q->where('expense_date', '>=', $from->toDateString());
        if ($to)   $q->where('expense_date', '<=', $to->toDateString());
        return (float) $q->sum('amount');
    }
}