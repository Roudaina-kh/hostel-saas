<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialReportController extends Controller
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

        return view('staff.financial.reports.index', compact('user', 'hostel'));
    }

    public function generate(Request $request)
    {
        return back()->with('success', 'Rapport généré avec succès (Simulé).');
    }
}