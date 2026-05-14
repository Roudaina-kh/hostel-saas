<?php

namespace App\Http\Controllers;

use App\Enums\ExpenseCategory;
use App\Models\Expense;
use App\Models\Hostel;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $hostelId = (int) session('hostel_id');
        $hostel   = Hostel::findOrFail($hostelId);

        return view('analytics.index', array_merge(
            ['hostel' => $hostel],
            $this->compute($hostelId)
        ));
    }

    public function data()
    {
        return response()->json($this->compute((int) session('hostel_id')));
    }

    // ─────────────────────────────────────────────────────────────────────────

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
