<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CashShift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class CashShiftController extends Controller
{
    private function getHostel()
    {
        $user = Auth::guard('staff')->user();
        return $user->hostels()
            ->where('hostels.id', session('staff_hostel_id'))
            ->wherePivot('status', 'active')
            ->first();
    }

    public function index()
    {
        $user   = Auth::guard('staff')->user();
        $hostel = $this->getHostel();

        if (!$hostel) {
            Auth::guard('staff')->logout();
            return redirect()->route('login')->with('error', 'Aucun hostel actif trouvé.');
        }

        $currentShift = null;
        if (Schema::hasTable('cash_shifts')) {
            $currentShift = CashShift::where('hostel_id', $hostel->id)
                ->where('status', 'open')
                ->first();
        }

        return view('staff.financial.cash-shifts.index', compact('currentShift', 'hostel', 'user'));
    }

    public function open(Request $request)
    {
        $request->validate([
            'opening_balance' => 'required|numeric|min:0',
        ]);

        $user   = Auth::guard('staff')->user();
        $hostel = $this->getHostel();

        if (!$hostel) {
            return back()->with('error', 'Aucun hostel actif trouvé.');
        }

        if (!Schema::hasTable('cash_shifts')) {
            return back()->with('error', 'Le module de caisse n\'est pas encore configuré.');
        }

        CashShift::create([
            'hostel_id'       => $hostel->id,
            'user_id'         => $user->id,
            'opening_balance' => $request->opening_balance,
            'opened_at'       => Carbon::now(),
            'status'          => 'open',
        ]);

        return redirect()->route('staff.financial.dashboard')
            ->with('success', 'Caisse ouverte avec succès.');
    }

    public function close(Request $request, CashShift $shift)
    {
        $request->validate([
            'closing_balance' => 'required|numeric|min:0',
        ]);

        $shift->update([
            'closing_balance' => $request->closing_balance,
            'closed_at'       => Carbon::now(),
            'status'          => 'closed',
            'notes'           => $request->notes,
        ]);

        return redirect()->route('staff.financial.dashboard')
            ->with('success', 'Caisse clôturée avec succès.');
    }
}