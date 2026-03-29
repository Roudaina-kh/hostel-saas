<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\OwnerRegisterController;
use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\TentSpaceController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminOwnerController;
use App\Http\Controllers\SuperAdmin\SuperAdminHostelController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerRoomController;
use App\Http\Controllers\Manager\ManagerTaxController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\FinancialDashboardController;
use App\Http\Controllers\Staff\FinancialReportController;
use App\Http\Controllers\Staff\CashShiftController;

// ═══════════════════════════════════════════════════════
// LANDING
// ═══════════════════════════════════════════════════════
Route::get('/', [App\Http\Controllers\LandingController::class, 'index'])->name('landing');

// ═══════════════════════════════════════════════════════
// AUTH UNIFIÉE (Login / Logout)
// ═══════════════════════════════════════════════════════
Route::get('/login', [UnifiedLoginController::class, 'showForm'])->name('login');
Route::post('/login', [UnifiedLoginController::class, 'login']);
Route::post('/logout', [UnifiedLoginController::class, 'logout'])->name('logout');

// ═══════════════════════════════════════════════════════
// REGISTER OWNER
// ═══════════════════════════════════════════════════════
Route::get('/register', [OwnerRegisterController::class, 'showForm'])->name('register');
Route::post('/register', [OwnerRegisterController::class, 'register']);

Route::get('/register-hostel', [App\Http\Controllers\HostelRequestController::class, 'create'])->name('register-hostel.create');
Route::post('/register-hostel', [App\Http\Controllers\HostelRequestController::class, 'store'])->name('register-hostel.store');

// ═══════════════════════════════════════════════════════
// REDIRECTIONS LEGACY
// ═══════════════════════════════════════════════════════
Route::get('/manager/login', fn() => redirect()->route('login'));
Route::get('/super-admin/login', fn() => redirect()->route('login'));

// ═══════════════════════════════════════════════════════
// OWNER ROUTES
// ═══════════════════════════════════════════════════════
Route::middleware(['auth'])->group(function () {

    // Onboarding
    Route::get('/onboarding', [HostelController::class, 'onboarding'])->name('onboarding.create');
    Route::post('/onboarding', [HostelController::class, 'storeFirst'])->name('onboarding.store');

    // Hostel switcher
    Route::post('/hostel/switch/{hostel}', [HostelController::class, 'switchHostel'])->name('hostel.switch');

    // CRUD Hostels
    Route::resource('hostels', HostelController::class)->except(['show']);

    // Zone protégée par hostel actif
    Route::middleware(['hostel.selected'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Rooms
        Route::resource('rooms', RoomController::class)->except(['show']);

        // Beds
        Route::get('/beds', [BedController::class, 'index'])->name('beds.index');
        Route::post('/beds', [BedController::class, 'store'])->name('beds.store');
        Route::put('/beds/{bed}', [BedController::class, 'update'])->name('beds.update');
        Route::delete('/beds/{bed}', [BedController::class, 'destroy'])->name('beds.destroy');
        Route::post('/beds/{bed}/toggle-maintenance', [BedController::class, 'toggleMaintenance'])->name('beds.toggle');

        // Tent Spaces
        Route::resource('tent-spaces', TentSpaceController::class)->except(['show']);

        // Pricing
        Route::resource('pricing', PricingController::class)->except(['show']);
        Route::post('/pricing/{pricing}/activate', [PricingController::class, 'activate'])->name('pricing.activate');

        // Taxes
        Route::get('/taxes', [TaxController::class, 'index'])->name('taxes.index');
        Route::put('/taxes', [TaxController::class, 'update'])->name('taxes.update');

        // Team (gestion users/managers par l'owner)
        Route::resource('managers', ManagerController::class)->except(['show']);
    });
});

// ═══════════════════════════════════════════════════════
// SUPER ADMIN ROUTES
// ═══════════════════════════════════════════════════════
Route::prefix('super-admin')->name('super-admin.')->middleware(['super_admin.auth'])->group(function () {

    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

    // Owners
    Route::get('/owners', [SuperAdminOwnerController::class, 'index'])->name('owners.index');
    Route::get('/owners/{owner}', [SuperAdminOwnerController::class, 'show'])->name('owners.show');
    Route::patch('/owners/{owner}/toggle', [SuperAdminOwnerController::class, 'toggle'])->name('owners.toggle');
    Route::delete('/owners/{owner}', [SuperAdminOwnerController::class, 'destroy'])->name('owners.destroy');

    // Hostels
    Route::get('/hostels', [SuperAdminHostelController::class, 'index'])->name('hostels.index');
    Route::get('/hostels/{hostel}', [SuperAdminHostelController::class, 'show'])->name('hostels.show');
    Route::patch('/hostels/{hostel}/toggle', [SuperAdminHostelController::class, 'toggle'])->name('hostels.toggle');
    Route::delete('/hostels/{hostel}', [SuperAdminHostelController::class, 'destroy'])->name('hostels.destroy');
});

// ═══════════════════════════════════════════════════════
// MANAGER ROUTES
// ═══════════════════════════════════════════════════════
Route::prefix('manager')->name('manager.')->middleware(['auth:staff', 'manager.auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

    // Rooms
    Route::resource('rooms', ManagerRoomController::class)->except(['show']);

    // ── Beds ──────────────────────────────────────────────────
    Route::get('/beds', function () {
        $user     = Auth::guard('staff')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();

        $beds = \App\Models\Bed::whereHas('room', function ($q) use ($hostelId) {
            $q->where('hostel_id', $hostelId);
        })->with('room')->get();

        $rooms = \App\Models\Room::where('hostel_id', $hostelId)
            ->where('type', 'dormitory')->get();

        return view('manager.beds.index', compact('hostel', 'user', 'beds', 'rooms'));
    })->name('beds.index');

    Route::post('/beds', function (\Illuminate\Http\Request $request) {
        $hostelId = session('staff_hostel_id');

        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $room = \App\Models\Room::where('id', $data['room_id'])
            ->where('hostel_id', $hostelId)
            ->firstOrFail();

        \App\Models\Bed::create([
            'name'    => $data['name'],
            'room_id' => $room->id,
            'hostel_id' => $hostelId,
        ]);

        return redirect()->route('manager.beds.index')
            ->with('success', 'Lit ajouté avec succès.');
    })->name('beds.store');

    Route::put('/beds/{bed}', function (\Illuminate\Http\Request $request, \App\Models\Bed $bed) {
        $hostelId = session('staff_hostel_id');
        abort_unless($bed->room->hostel_id === (int) $hostelId, 403);

        $bed->update($request->validate([
            'name' => 'required|string|max:100',
        ]));

        return redirect()->route('manager.beds.index')
            ->with('success', 'Lit mis à jour.');
    })->name('beds.update');

    Route::delete('/beds/{bed}', function (\App\Models\Bed $bed) {
        $hostelId = session('staff_hostel_id');
        abort_unless($bed->room->hostel_id === (int) $hostelId, 403);
        $bed->delete();

        return redirect()->route('manager.beds.index')
            ->with('success', 'Lit supprimé.');
    })->name('beds.destroy');

    Route::post('/beds/{bed}/toggle-maintenance', function (\App\Models\Bed $bed) {
        $hostelId = session('staff_hostel_id');
        abort_unless($bed->room->hostel_id === (int) $hostelId, 403);
        $bed->update(['maintenance' => !$bed->maintenance]);

        return response()->json([
            'success'     => true,
            'maintenance' => $bed->maintenance,
        ]);
    })->name('beds.toggle-maintenance');

    // ── Pricing ───────────────────────────────────────────────
    Route::get('/pricing', function () {
        $user     = Auth::guard('staff')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();

        $prices = \App\Models\RoomPrice::whereHas('room', function ($q) use ($hostelId) {
            $q->where('hostel_id', $hostelId);
        })->with('room')->latest()->get();

        $rooms = \App\Models\Room::where('hostel_id', $hostelId)->get();

        return view('manager.pricing.index', compact('hostel', 'user', 'prices', 'rooms'));
    })->name('pricing.index');

    Route::post('/pricing', function (\Illuminate\Http\Request $request) {
        $hostelId = session('staff_hostel_id');

        $data = $request->validate([
            'room_id'      => 'required|exists:rooms,id',
            'price_amount' => 'required|numeric|min:0',
            'currency'     => 'required|string|max:10',
            'valid_from'   => 'nullable|date',
            'valid_to'     => 'nullable|date|after_or_equal:valid_from',
            'is_active'    => 'boolean',
        ]);

        $data['hostel_id'] = $hostelId;
        $data['is_active'] = $request->boolean('is_active');

        if ($data['is_active']) {
            \App\Models\RoomPrice::where('room_id', $data['room_id'])
                ->where('hostel_id', $hostelId)
                ->update(['is_active' => false]);
        }

        \App\Models\RoomPrice::create($data);

        return redirect()->route('manager.pricing.index')
            ->with('success', 'Tarif ajouté.');
    })->name('pricing.store');

    Route::delete('/pricing/{pricing}', function (\App\Models\RoomPrice $pricing) {
        $hostelId = session('staff_hostel_id');
        abort_unless($pricing->hostel_id === (int) $hostelId, 403);
        $pricing->delete();

        return redirect()->route('manager.pricing.index')
            ->with('success', 'Tarif supprimé.');
    })->name('pricing.destroy');

    // ── Taxes ─────────────────────────────────────────────────
    Route::get('/taxes', [ManagerTaxController::class, 'index'])->name('taxes.index');
    Route::put('/taxes', [ManagerTaxController::class, 'update'])->name('taxes.update');

    // ── Staff / Équipe ────────────────────────────────────────
    Route::get('/staff', function () {
        $user     = Auth::guard('staff')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();

        $staff = \App\Models\User::whereHas('hostels', function ($q) use ($hostelId) {
            $q->where('hostels.id', $hostelId);
        })->with(['hostels' => function ($q) use ($hostelId) {
            $q->where('hostels.id', $hostelId);
        }])->get()->map(function ($u) {
            $u->roleInHostel  = $u->hostels->first()?->pivot->role;
            $u->statusInHostel = $u->hostels->first()?->pivot->status;
            return $u;
        });

        return view('manager.staff.index', compact('hostel', 'user', 'staff'));
    })->name('staff.index');

    Route::post('/staff', function (\Illuminate\Http\Request $request) {
        $hostelId = session('staff_hostel_id');

        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone'    => 'nullable|string|max:30',
            'role'     => 'required|in:manager,staff,financial',
        ]);

        $newUser = \App\Models\User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
            'status'   => 'active',
        ]);

        $newUser->hostels()->attach($hostelId, [
            'role'   => $data['role'],
            'status' => 'active',
        ]);

        return redirect()->route('manager.staff.index')
            ->with('success', 'Membre de l\'équipe ajouté.');
    })->name('staff.store');

    Route::delete('/staff/{staffUser}', function (\App\Models\User $staffUser) {
        $hostelId = session('staff_hostel_id');

        // Retirer uniquement l'affectation au hostel (pas supprimer le user)
        $staffUser->hostels()->detach($hostelId);

        return redirect()->route('manager.staff.index')
            ->with('success', 'Membre retiré de l\'équipe.');
    })->name('staff.destroy');

    // ── Settings Hostel ───────────────────────────────────────
    Route::get('/settings', function () {
        $user     = Auth::guard('staff')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();

        return view('manager.settings.edit', compact('hostel', 'user'));
    })->name('settings.edit');

    Route::put('/settings', function (\Illuminate\Http\Request $request) {
        $user     = Auth::guard('staff')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();

        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'city'    => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:150',
        ]);

        $hostel->update($data);

        return redirect()->route('manager.settings.edit')
            ->with('success', 'Paramètres mis à jour.');
    })->name('settings.update');
});

// ═══════════════════════════════════════════════════════
// STAFF & FINANCIAL ROUTES
// ═══════════════════════════════════════════════════════
Route::prefix('staff')->name('staff.')->middleware(['auth:staff'])->group(function () {

    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    // Financial
    Route::get('/financial/dashboard', [FinancialDashboardController::class, 'index'])
        ->name('financial.dashboard');
    Route::get('/financial/reports', [FinancialReportController::class, 'index'])
        ->name('financial.reports.index');
    Route::post('/financial/reports/generate', [FinancialReportController::class, 'generate'])
        ->name('financial.reports.generate');

    // Cash shifts
    Route::get('/cash-shifts', [CashShiftController::class, 'index'])
        ->name('cash-shifts.index');
    Route::post('/cash-shifts/open', [CashShiftController::class, 'open'])
        ->name('cash-shifts.open');
    Route::post('/cash-shifts/close/{shift}', [CashShiftController::class, 'close'])
        ->name('cash-shifts.close');
});