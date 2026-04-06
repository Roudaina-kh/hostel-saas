<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class FinancialDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('user')->user();

        // Nouvelle structure : hostel via pivot
        $hostelId = session('staff_hostel_id');
        $hostel = $user->hostels()
            ->where('hostels.id', $hostelId)
            ->wherePivot('status', 'active')
            ->first();

        if (!$hostel) {
            Auth::guard('user')->logout();
            return redirect()->route('login')
                ->with('error', 'Aucun hostel actif trouvé.');
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth   = Carbon::now()->endOfMonth();

        $monthlyRevenue  = 0;
        $monthlyExpenses = 0;
        $totalBeds       = 0;

        if (Schema::hasTable('payments')) {
            $monthlyRevenue = Payment::whereHas('reservation', function ($q) use ($hostel) {
                $q->where('hostel_id', $hostel->id);
            })
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        }

        if (Schema::hasTable('expenses')) {
            $monthlyExpenses = Expense::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->sum('amount');
        }

        if (Schema::hasTable('rooms') && Schema::hasTable('beds')) {
            $totalBeds = $hostel->rooms()->withCount('beds')->get()->sum('beds_count');
        }

        $netProfit = $monthlyRevenue - $monthlyExpenses;

        return view('staff.financial.dashboard', compact(
            'user',
            'hostel',
            'monthlyRevenue',
            'monthlyExpenses',
            'netProfit',
            'totalBeds'
        ));
    }
}