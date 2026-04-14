<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\OwnerAuthController;
use App\Http\Controllers\Auth\SuperAdminAuthController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\OwnerRegisterController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\TentSpaceController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\ExtraStockMovementController;
use App\Http\Controllers\InventoryBlockController;
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
// OWNER AUTH
// ═══════════════════════════════════════════════════════
Route::get('/login', [OwnerAuthController::class, 'create'])->name('owner.login');
Route::post('/login', [OwnerAuthController::class, 'store'])->name('owner.login.store');

Route::middleware('auth:owner')->group(function () {
    Route::post('/logout', [OwnerAuthController::class, 'destroy'])->name('owner.logout');
});

// ═══════════════════════════════════════════════════════
// SUPER ADMIN AUTH
// ═══════════════════════════════════════════════════════
Route::prefix('super-admin')->name('super-admin.')->group(function () {

    Route::middleware('guest:super_admin')->group(function () {
        Route::get('/login', [SuperAdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [SuperAdminAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth:super_admin')->group(function () {
        Route::post('/logout', [SuperAdminAuthController::class, 'destroy'])->name('logout');
        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/owners', [SuperAdminOwnerController::class, 'index'])->name('owners.index');
        Route::get('/owners/{owner}', [SuperAdminOwnerController::class, 'show'])->name('owners.show');
        Route::patch('/owners/{owner}/toggle', [SuperAdminOwnerController::class, 'toggle'])->name('owners.toggle');
        Route::delete('/owners/{owner}', [SuperAdminOwnerController::class, 'destroy'])->name('owners.destroy');
        Route::get('/hostels', [SuperAdminHostelController::class, 'index'])->name('hostels.index');
        Route::get('/hostels/{hostel}', [SuperAdminHostelController::class, 'show'])->name('hostels.show');
        Route::patch('/hostels/{hostel}/toggle', [SuperAdminHostelController::class, 'toggle'])->name('hostels.toggle');
        Route::delete('/hostels/{hostel}', [SuperAdminHostelController::class, 'destroy'])->name('hostels.destroy');
    });
});

// ═══════════════════════════════════════════════════════
// USER AUTH (staff / manager / financial)
// ═══════════════════════════════════════════════════════
Route::prefix('user')->name('user.')->group(function () {

    Route::middleware('guest:user')->group(function () {
        Route::get('/login', [UserAuthController::class, 'create'])->name('login');
        Route::post('/login', [UserAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth:user')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'destroy'])->name('logout');
    });
});

// ═══════════════════════════════════════════════════════
// REDIRECTIONS LEGACY
// ═══════════════════════════════════════════════════════
Route::get('/manager/login', fn() => redirect()->route('user.login'));

// ═══════════════════════════════════════════════════════
// REGISTER OWNER
// ═══════════════════════════════════════════════════════
Route::get('/register', [OwnerRegisterController::class, 'showForm'])->name('register');
Route::post('/register', [OwnerRegisterController::class, 'register']);

Route::get('/register-hostel', [App\Http\Controllers\HostelRequestController::class, 'create'])->name('register-hostel.create');
Route::post('/register-hostel', [App\Http\Controllers\HostelRequestController::class, 'store'])->name('register-hostel.store');

// ═══════════════════════════════════════════════════════
// OWNER ROUTES
// ═══════════════════════════════════════════════════════
Route::middleware('auth:owner')->group(function () {

    Route::get('/onboarding', [HostelController::class, 'onboarding'])->name('onboarding.create');
    Route::post('/onboarding', [HostelController::class, 'storeFirst'])->name('onboarding.store');

    Route::post('/hostel/switch/{hostel}', [HostelController::class, 'switchHostel'])->name('hostel.switch');

    Route::resource('hostels', HostelController::class)->except(['show']);

    Route::middleware('hostel.selected')->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ── Rooms ──────────────────────────────────────────
        Route::resource('rooms', RoomController::class)->except(['show']);

        // ── Beds ───────────────────────────────────────────
        Route::get('/beds', [BedController::class, 'index'])->name('beds.index');
        Route::post('/beds', [BedController::class, 'store'])->name('beds.store');
        Route::put('/beds/{bed}', [BedController::class, 'update'])->name('beds.update');
        Route::post('/beds/{bed}/toggle', [BedController::class, 'toggleEnabled'])->name('beds.toggle');
        Route::delete('/beds/{bed}', [BedController::class, 'destroy'])->name('beds.destroy');

        // ── Tent Spaces ────────────────────────────────────
        Route::resource('tent-spaces', TentSpaceController::class)->except(['show']);
        Route::post('/tent-spaces/{tentSpace}/toggle', [TentSpaceController::class, 'toggle'])->name('tent-spaces.toggle');

        // ── Prices (Sprint 2) ──────────────────────────────
        Route::resource('prices', PriceController::class)->except(['show']);

        // ── Taxes (Sprint 2) ───────────────────────────────
        Route::resource('taxes', TaxController::class)->except(['show']);
        Route::post('/taxes/{tax}/toggle', [TaxController::class, 'toggleEnabled'])->name('taxes.toggle');

        // ── Extras (Sprint 2) ──────────────────────────────
        Route::resource('extras', ExtraController::class)->except(['show']);
        Route::get('/extras/{extra}/movements', [ExtraStockMovementController::class, 'index'])->name('extras.movements');
        Route::post('/extras/{extra}/movements', [ExtraStockMovementController::class, 'store'])->name('extras.movements.store');
        Route::delete('/extras/movements/{extraStockMovement}', [ExtraStockMovementController::class, 'destroy'])->name('extras.movements.destroy');

        // ── Inventory Blocks (Sprint 2) ────────────────────
        Route::resource('inventory-blocks', InventoryBlockController::class)->except(['show', 'create', 'edit']);

        // ── Team ───────────────────────────────────────────
        Route::resource('managers', ManagerController::class)->except(['show']);
    });
});

// ═══════════════════════════════════════════════════════
// MANAGER ROUTES
// ═══════════════════════════════════════════════════════
Route::prefix('manager')->name('manager.')->middleware(['auth:user', 'manager.auth'])->group(function () {

    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

    Route::resource('rooms', ManagerRoomController::class)->except(['show']);

    // ── Helper closure : données communes pricing ──────
    $pricingData = function () {
        $user     = Auth::guard('user')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();

        $currentManager = (object) [
            'can_manage_pricing' => true,
        ];

        $prices = \App\Models\Price::where('hostel_id', $hostelId)
            ->with(['priceable', 'taxes'])
            ->whereNotNull('priceable_type')
            ->latest()
            ->get();

        $rooms      = \App\Models\Room::where('hostel_id', $hostelId)->get();
        $tentSpaces = \App\Models\TentSpace::where('hostel_id', $hostelId)->get();
        $extras     = \App\Models\Extra::where('hostel_id', $hostelId)->get();
        $taxes      = \App\Models\Tax::where('hostel_id', $hostelId)->where('is_enabled', true)->get();

        return compact('user', 'hostel', 'hostelId', 'currentManager', 'prices', 'rooms', 'tentSpaces', 'extras', 'taxes');
    };

    // ── Pricing : INDEX ───────────────────────────────
    Route::get('/pricing', function () use ($pricingData) {
        $data = $pricingData();
        return view('manager.pricing.index', $data);
    })->name('pricing.index');

    // ── Pricing : CREATE ──────────────────────────────
    Route::get('/pricing/create', function () use ($pricingData) {
        $data = $pricingData();
        return view('manager.pricing.create', $data);
    })->name('pricing.create');

    // ── Pricing : STORE ───────────────────────────────
    Route::post('/pricing', function (\Illuminate\Http\Request $request) {
        $hostelId = session('staff_hostel_id');

        $data = $request->validate([
            'priceable_type' => 'required|in:room,tent_space,extra',
            'priceable_id'   => 'required|integer',
            'pricing_mode'   => 'required|in:per_room,per_bed,per_person,per_unit,per_night,per_person_per_night',
            'price_ht'       => 'required|numeric|min:0',
            'price_ttc'      => 'required|numeric|min:0',
            'valid_from'     => 'required|date',
            'valid_to'       => 'nullable|date|after_or_equal:valid_from',
            'tax_ids'        => 'nullable|array',
            'tax_ids.*'      => 'exists:taxes,id',
        ]);

        // Le morphMap dans AppServiceProvider gère la conversion type court → classe
        $data['hostel_id'] = $hostelId;

        $taxIds = $data['tax_ids'] ?? [];
        unset($data['tax_ids']);

        $price = \App\Models\Price::create($data);

        if (!empty($taxIds)) {
            $price->taxes()->sync($taxIds);
        }

        return redirect()->route('manager.pricing.index')->with('success', 'Tarif ajouté.');
    })->name('pricing.store');

    // ── Pricing : EDIT ────────────────────────────────
    Route::get('/pricing/{price}/edit', function (\App\Models\Price $price) use ($pricingData) {
        abort_unless($price->hostel_id === (int) session('staff_hostel_id'), 403);
        $data          = $pricingData();
        $data['price'] = $price->load(['priceable', 'taxes']);
        return view('manager.pricing.edit', $data);
    })->name('pricing.edit');

    // ── Pricing : UPDATE ──────────────────────────────
    Route::put('/pricing/{price}', function (\Illuminate\Http\Request $request, \App\Models\Price $price) {
        abort_unless($price->hostel_id === (int) session('staff_hostel_id'), 403);

        $data = $request->validate([
            'priceable_type' => 'required|in:room,tent_space,extra',
            'priceable_id'   => 'required|integer',
            'pricing_mode'   => 'required|in:per_room,per_bed,per_person,per_unit,per_night,per_person_per_night',
            'price_ht'       => 'required|numeric|min:0',
            'price_ttc'      => 'required|numeric|min:0',
            'valid_from'     => 'required|date',
            'valid_to'       => 'nullable|date|after_or_equal:valid_from',
            'tax_ids'        => 'nullable|array',
            'tax_ids.*'      => 'exists:taxes,id',
        ]);

        // Le morphMap dans AppServiceProvider gère la conversion type court → classe
        $taxIds = $data['tax_ids'] ?? [];
        unset($data['tax_ids']);

        $price->update($data);
        $price->taxes()->sync($taxIds);

        return redirect()->route('manager.pricing.index')->with('success', 'Tarif mis à jour.');
    })->name('pricing.update');

    // ── Pricing : DESTROY ─────────────────────────────
    Route::delete('/pricing/{price}', function (\App\Models\Price $price) {
        abort_unless($price->hostel_id === (int) session('staff_hostel_id'), 403);
        $price->taxes()->detach();
        $price->delete();
        return redirect()->route('manager.pricing.index')->with('success', 'Tarif supprimé.');
    })->name('pricing.destroy');

    // ── Beds ──────────────────────────────────────────
    Route::get('/beds', function () {
        $user     = Auth::guard('user')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();
        $beds     = \App\Models\Bed::whereHas('room', fn($q) => $q->where('hostel_id', $hostelId))->with('room')->get();
        $rooms    = \App\Models\Room::where('hostel_id', $hostelId)->where('type', 'dormitory')->get();
        return view('manager.beds.index', compact('hostel', 'user', 'beds', 'rooms'));
    })->name('beds.index');

    Route::post('/beds', function (\Illuminate\Http\Request $request) {
        $hostelId = session('staff_hostel_id');
        $data     = $request->validate(['name' => 'required|string|max:100', 'room_id' => 'required|exists:rooms,id']);
        $room     = \App\Models\Room::where('id', $data['room_id'])->where('hostel_id', $hostelId)->firstOrFail();
        \App\Models\Bed::create(['name' => $data['name'], 'room_id' => $room->id]);
        return redirect()->route('manager.beds.index')->with('success', 'Lit ajouté avec succès.');
    })->name('beds.store');

    Route::put('/beds/{bed}', function (\Illuminate\Http\Request $request, \App\Models\Bed $bed) {
        abort_unless($bed->room->hostel_id === (int) session('staff_hostel_id'), 403);
        $bed->update($request->validate(['name' => 'required|string|max:100']));
        return redirect()->route('manager.beds.index')->with('success', 'Lit mis à jour.');
    })->name('beds.update');

    Route::delete('/beds/{bed}', function (\App\Models\Bed $bed) {
        abort_unless($bed->room->hostel_id === (int) session('staff_hostel_id'), 403);
        $bed->delete();
        return redirect()->route('manager.beds.index')->with('success', 'Lit supprimé.');
    })->name('beds.destroy');

    // ── Taxes ─────────────────────────────────────────
    Route::get('/taxes', [ManagerTaxController::class, 'index'])->name('taxes.index');
    Route::put('/taxes', [ManagerTaxController::class, 'update'])->name('taxes.update');

    // ── Staff ─────────────────────────────────────────
    Route::get('/staff', function () {
        $user     = Auth::guard('user')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();
        $staff    = \App\Models\User::whereHas('hostels', fn($q) => $q->where('hostels.id', $hostelId))
            ->with(['hostels' => fn($q) => $q->where('hostels.id', $hostelId)])
            ->get()->map(function ($u) {
                $u->roleInHostel   = $u->hostels->first()?->pivot->role;
                $u->statusInHostel = $u->hostels->first()?->pivot->status;
                return $u;
            });
        return view('manager.staff.index', compact('hostel', 'user', 'staff'));
    })->name('staff.index');

    Route::post('/staff', function (\Illuminate\Http\Request $request) {
        $hostelId = session('staff_hostel_id');
        $data     = $request->validate([
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
        $newUser->hostels()->attach($hostelId, ['role' => $data['role'], 'status' => 'active']);
        return redirect()->route('manager.staff.index')->with('success', 'Membre ajouté.');
    })->name('staff.store');

    Route::delete('/staff/{staffUser}', function (\App\Models\User $staffUser) {
        $staffUser->hostels()->detach(session('staff_hostel_id'));
        return redirect()->route('manager.staff.index')->with('success', 'Membre retiré.');
    })->name('staff.destroy');

    // ── Settings ──────────────────────────────────────
    Route::get('/settings', function () {
        $user     = Auth::guard('user')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();
        return view('manager.settings.edit', compact('hostel', 'user'));
    })->name('settings.edit');

    Route::put('/settings', function (\Illuminate\Http\Request $request) {
        $user     = Auth::guard('user')->user();
        $hostelId = session('staff_hostel_id');
        $hostel   = $user->hostels()->where('hostels.id', $hostelId)->first();
        $hostel->update($request->validate([
            'name'    => 'required|string|max:150',
            'address' => 'nullable|string|max:255',
            'city'    => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'email'   => 'nullable|email|max:150',
        ]));
        return redirect()->route('manager.settings.edit')->with('success', 'Paramètres mis à jour.');
    })->name('settings.update');
});

// ═══════════════════════════════════════════════════════
// STAFF & FINANCIAL ROUTES
// ═══════════════════════════════════════════════════════
Route::prefix('staff')->name('staff.')->middleware('auth:user')->group(function () {

    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');

    Route::get('/financial/dashboard', [FinancialDashboardController::class, 'index'])->name('financial.dashboard');
    Route::get('/financial/reports', [FinancialReportController::class, 'index'])->name('financial.reports.index');
    Route::post('/financial/reports/generate', [FinancialReportController::class, 'generate'])->name('financial.reports.generate');

    Route::get('/cash-shifts', [CashShiftController::class, 'index'])->name('cash-shifts.index');
    Route::post('/cash-shifts/open', [CashShiftController::class, 'open'])->name('cash-shifts.open');
});