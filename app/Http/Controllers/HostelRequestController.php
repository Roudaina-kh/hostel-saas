<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HostelRequest;

class HostelRequestController extends Controller
{
    public function create()
    {
        return view('register-hostel');
    }

    public function store(Request $request)
    {
        // 🚨 Robust check for SQLite
        try {
            \Illuminate\Support\Facades\DB::statement('CREATE TABLE IF NOT EXISTS hostel_requests (id INTEGER PRIMARY KEY AUTOINCREMENT, hostel_name TEXT, first_name TEXT, last_name TEXT, email TEXT, country TEXT DEFAULT "Tunisie", city TEXT, phone TEXT, skype_id TEXT, channel_manager TEXT, status TEXT DEFAULT "pending", created_at DATETIME, updated_at DATETIME)');
        } catch(\Exception $e) {}

        $validated = $request->validate([
            'hostel_name'    => 'required|string|max:255',
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'country'        => 'required|string|max:255',
            'city'           => 'required|string|max:255',
            'phone'          => 'required|string|max:255',
            'channel_manager' => 'nullable|string|max:255',
        ]);

        $hostelRequest = HostelRequest::create($validated);

        // Notify Admins, Managers, and Staff
        try {
            $recipients = \App\Models\User::whereIn('role', ['super_admin', 'manager', 'staff'])->get();
            \Illuminate\Support\Facades\Notification::send($recipients, new \App\Notifications\NewHostelRequestReceived($hostelRequest));
        } catch (\Exception $e) {
            // Silently fail if notification table or mailing is not setup
            \Illuminate\Support\Facades\Log::error("Notification failed: " . $e->getMessage());
        }

        return back()->with('success', 'Merci d\'envoyer votre demande. Notre équipe reviendra vers vous très prochainement.');
    }
}
