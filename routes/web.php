<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\OwnerAuthController;
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
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SuperAdmin\SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\SuperAdminOwnerController;
use App\Http\Controllers\SuperAdmin\SuperAdminHostelController;
use App\Http\Controllers\SuperAdmin\SuperAdminAuthController;
use App\Http\Controllers\SuperAdmin\SuperAdminManagerController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Manager\ManagerRoomController;
use App\Http\Controllers\Manager\ManagerTaxController;
use App\Http\Controllers\Manager\ManagerExchangeRateController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\FinancialDashboardController;
use App\Http\Controllers\Staff\FinancialReportController;
use App\Http\Controllers\Staff\CashShiftController;
use App\Http\Controllers\Manager\ManagerInventoryBlockController;
use App\Http\Controllers\Reservation\CreateReservationController;
use App\Http\Controllers\Reservation\ManagerReservationController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\Staff\StaffExpenseController;


// ═══════════════════════════════════════════════
// LANDING & CONTACT (routes publiques)
// ═══════════════════════════════════════════════
Route::get('/', [\App\Http\Controllers\SearchController::class, 'index'])->name('landing');

Route::post('/contact', [\App\Http\Controllers\ContactRequestController::class, 'store'])
    ->name('contact.store');
Route::get('/reserve/{hostel}', [\App\Http\Controllers\ContactRequestController::class, 'create'])
    ->name('contact.create');
Route::get('/search',              [SearchController::class, 'index'])->name('search.index');
Route::get('/search/regions',      [SearchController::class, 'regions'])->name('search.regions');
Route::get('/search/availability', [SearchController::class, 'availability'])->name('search.availability');
Route::get('/explore/{id}',        [SearchController::class, 'show'])->name('search.show');

// ═══════════════════════════════════════════════
// OWNER AUTH
// ═══════════════════════════════════════════════
Route::get('/login',  [OwnerAuthController::class, 'create'])->name('owner.login');
Route::post('/login', [OwnerAuthController::class, 'store'])->name('owner.login.store');
Route::post('/logout', [OwnerAuthController::class, 'destroy'])->name('owner.logout');

// ═══════════════════════════════════════════════════════
// SUPER ADMIN
// ═══════════════════════════════════════════════════════
Route::prefix('super-admin')->name('super-admin.')->group(function () {

    Route::middleware('guest:super_admin')->group(function () {
        Route::get('/login',  [SuperAdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [SuperAdminAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth:super_admin')->group(function () {
        Route::post('/logout', [SuperAdminAuthController::class, 'destroy'])->name('logout');

        Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');

        // Propriétaires
        Route::get('/owners',                    [SuperAdminOwnerController::class, 'index'])->name('owners.index');
        Route::get('/owners/create',             [SuperAdminOwnerController::class, 'create'])->name('owners.create');
        Route::post('/owners',                   [SuperAdminOwnerController::class, 'store'])->name('owners.store');
        Route::get('/owners/{owner}',            [SuperAdminOwnerController::class, 'show'])->name('owners.show');
        Route::patch('/owners/{owner}/toggle',   [SuperAdminOwnerController::class, 'toggle'])->name('owners.toggle');
        Route::delete('/owners/{owner}',         [SuperAdminOwnerController::class, 'destroy'])->name('owners.destroy');

        // Hostels
        Route::get('/hostels',                   [SuperAdminHostelController::class, 'index'])->name('hostels.index');
        Route::get('/hostels/{hostel}',          [SuperAdminHostelController::class, 'show'])->name('hostels.show');
        Route::patch('/hostels/{hostel}/toggle', [SuperAdminHostelController::class, 'toggle'])->name('hostels.toggle');
        Route::delete('/hostels/{hostel}',       [SuperAdminHostelController::class, 'destroy'])->name('hostels.destroy');

        // Managers
        Route::get('/managers',                  [SuperAdminManagerController::class, 'index'])->name('managers.index');
        Route::patch('/managers/{user}/toggle',  [SuperAdminManagerController::class, 'toggle'])->name('managers.toggle');
    });
});

// ═══════════════════════════════════════════════════════
// USER AUTH
// ═══════════════════════════════════════════════════════
Route::prefix('user')->name('user.')->group(function () {

    Route::middleware('guest:user')->group(function () {
        Route::get('/login',  [UserAuthController::class, 'create'])->name('login');
        Route::post('/login', [UserAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth:user')->group(function () {
        Route::post('/logout', [UserAuthController::class, 'destroy'])->name('logout');
        // NOTE: pas de routes expenses ici — elles sont dans manager. et staff.
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

        // ── Dépenses (owner) ─────────────────────────────────────────────
        Route::post('/expenses/check-password', [ExpenseController::class, 'checkPassword'])
            ->name('expenses.check-password');
        Route::resource('expenses', ExpenseController::class)->except(['show']);

        // ── Dashboard ────────────────────────────────────────────────────
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // ── Rooms / Beds / Tent Spaces ───────────────────────────────────
        Route::resource('rooms', RoomController::class)->except(['show']);

        Route::get('/beds', [BedController::class, 'index'])->name('beds.index');
        Route::post('/beds', [BedController::class, 'store'])->name('beds.store');
        Route::put('/beds/{bed}', [BedController::class, 'update'])->name('beds.update');
        Route::post('/beds/{bed}/toggle', [BedController::class, 'toggleEnabled'])->name('beds.toggle');
        Route::delete('/beds/{bed}', [BedController::class, 'destroy'])->name('beds.destroy');

        Route::resource('tent-spaces', TentSpaceController::class)->except(['show']);
        Route::post('/tent-spaces/{tentSpace}/toggle', [TentSpaceController::class, 'toggle'])->name('tent-spaces.toggle');

        // ── Prices / Taxes ───────────────────────────────────────────────
        Route::resource('prices', PriceController::class)->except(['show']);

        Route::resource('taxes', TaxController::class)->except(['show']);
        Route::post('/taxes/{tax}/toggle', [TaxController::class, 'toggleEnabled'])->name('taxes.toggle');

        // ── Extras / Stock ───────────────────────────────────────────────
        Route::resource('extras', ExtraController::class)->except(['show']);
        Route::get('/extras/{extra}/movements', [ExtraStockMovementController::class, 'index'])->name('extras.movements');
        Route::post('/extras/{extra}/movements', [ExtraStockMovementController::class, 'store'])->name('extras.movements.store');
        Route::delete('/extras/movements/{extraStockMovement}', [ExtraStockMovementController::class, 'destroy'])->name('extras.movements.destroy');

        // ── Inventory Blocks ─────────────────────────────────────────────
        Route::resource('inventory-blocks', InventoryBlockController::class)->except(['show', 'create', 'edit']);

        // ── Managers ─────────────────────────────────────────────────────
        Route::resource('managers', ManagerController::class)->except(['show']);

        // ── Exchange Rates ───────────────────────────────────────────────
        Route::resource('exchange-rates', ExchangeRateController::class)
            ->only(['index', 'create', 'store', 'show']);

        // ── Contact Requests ─────────────────────────────────────────────
        Route::get('/contact-requests', [\App\Http\Controllers\ContactRequestController::class, 'index'])->name('contact-requests.index');
        Route::patch('/contact-requests/{contactRequest}/mark-read', [\App\Http\Controllers\ContactRequestController::class, 'markRead'])->name('contact-requests.mark-read');
        Route::patch('/contact-requests/{contactRequest}/mark-replied', [\App\Http\Controllers\ContactRequestController::class, 'markReplied'])->name('contact-requests.mark-replied');
        Route::delete('/contact-requests/{contactRequest}', [\App\Http\Controllers\ContactRequestController::class, 'destroy'])->name('contact-requests.destroy');
        Route::patch('/contact-requests/{contactRequest}/confirm', [\App\Http\Controllers\ContactRequestController::class, 'confirm'])->name('contact-requests.confirm');
        Route::patch('/contact-requests/{contactRequest}/cancel',  [\App\Http\Controllers\ContactRequestController::class, 'cancel'])->name('contact-requests.cancel');

        // ── Reservations (owner) ─────────────────────────────────────────
        Route::get('/reservations',                 [CreateReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/create',          [CreateReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations',                [CreateReservationController::class, 'store'])->name('reservations.store');
        Route::get('/reservations/available-units', [CreateReservationController::class, 'availableUnits'])->name('reservations.available-units');
        Route::post('/reservations/check-password', [CreateReservationController::class, 'checkPassword'])->name('reservations.check-password');
        Route::get('/reservations/{id}/edit',       [CreateReservationController::class, 'edit'])->name('reservations.edit');
        Route::put('/reservations/{id}',            [CreateReservationController::class, 'update'])->name('reservations.update');
        Route::delete('/reservations/{id}',         [CreateReservationController::class, 'destroy'])->name('reservations.destroy');

        // ── Payments (owner) ─────────────────────────────────────────────
        Route::get('/payments/reservation/{reservation}/people', [PaymentController::class, 'people'])->name('payments.people');
        Route::resource('payments', PaymentController::class);

        // ── Analytics (owner) ────────────────────────────────────────────
        Route::get('/analytics',      [\App\Http\Controllers\AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('/analytics/data', [\App\Http\Controllers\AnalyticsController::class, 'data'])->name('analytics.data');

    }); // fin hostel.selected
}); // fin auth:owner

// ═══════════════════════════════════════════════════════
// MANAGER ROUTES
// ═══════════════════════════════════════════════════════
Route::prefix('manager')->name('manager.')->middleware(['auth:user', 'manager.auth'])->group(function () {

    // ── Dépenses (manager) ───────────────────────────────────────────────
    Route::post('/expenses/check-password', [StaffExpenseController::class, 'checkPassword'])
        ->name('expenses.check-password');
    Route::resource('expenses', StaffExpenseController::class)->except(['show']);

    // ── Dashboard ────────────────────────────────────────────────────────
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

    // ── Inventory / Rooms ────────────────────────────────────────────────
    Route::resource('inventory-blocks', ManagerInventoryBlockController::class)->except(['show']);
    Route::resource('rooms', ManagerRoomController::class)->except(['show']);

    // ── Exchange Rates ───────────────────────────────────────────────────
    Route::resource('exchange-rates', ManagerExchangeRateController::class)->only(['index', 'create', 'store', 'show']);

    // ── Contact Requests ─────────────────────────────────────────────────
    Route::get('/contact-requests', [\App\Http\Controllers\ContactRequestController::class, 'index'])->name('contact-requests.index');
    Route::patch('/contact-requests/{contactRequest}/mark-read', [\App\Http\Controllers\ContactRequestController::class, 'markRead'])->name('contact-requests.mark-read');
    Route::patch('/contact-requests/{contactRequest}/mark-replied', [\App\Http\Controllers\ContactRequestController::class, 'markReplied'])->name('contact-requests.mark-replied');
    Route::delete('/contact-requests/{contactRequest}', [\App\Http\Controllers\ContactRequestController::class, 'destroy'])->name('contact-requests.destroy');
    Route::patch('/contact-requests/{contactRequest}/confirm', [\App\Http\Controllers\ContactRequestController::class, 'confirm'])->name('contact-requests.confirm');
    Route::patch('/contact-requests/{contactRequest}/cancel',  [\App\Http\Controllers\ContactRequestController::class, 'cancel'])->name('contact-requests.cancel');

    // ── Reservations (manager) ───────────────────────────────────────────
    Route::get('/reservations',                 [ManagerReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/create',          [ManagerReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations',                [ManagerReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/available-units', [ManagerReservationController::class, 'availableUnits'])->name('reservations.available-units');
    Route::post('/reservations/check-password', [ManagerReservationController::class, 'checkPassword'])->name('reservations.check-password');
    Route::get('/reservations/{id}/edit',       [ManagerReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{id}',            [ManagerReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{id}',         [ManagerReservationController::class, 'destroy'])->name('reservations.destroy');

    // ── Payments (manager) ───────────────────────────────────────────────
    Route::get('/payments/reservation/{reservation}/people', [\App\Http\Controllers\Manager\ManagerPaymentController::class, 'people'])->name('payments.people');
    Route::resource('payments', \App\Http\Controllers\Manager\ManagerPaymentController::class);

    // ── Pricing ──────────────────────────────────────────────────────────
    $pricingData = function () {
        $hostelId       = session('staff_hostel_id');
        $prices         = \App\Models\Price::where('hostel_id', $hostelId)->with(['priceable', 'taxes'])->whereNotNull('priceable_type')->latest()->get();
        $rooms          = \App\Models\Room::where('hostel_id', $hostelId)->get();
        $tentSpaces     = \App\Models\TentSpace::where('hostel_id', $hostelId)->get();
        $extras         = \App\Models\Extra::where('hostel_id', $hostelId)->get();
        $taxes          = \App\Models\Tax::where('hostel_id', $hostelId)->where('is_enabled', true)->get();
        $currentManager = (object) ['can_manage_pricing' => true];
        return compact('hostelId', 'currentManager', 'prices', 'rooms', 'tentSpaces', 'extras', 'taxes');
    };

    Route::get('/pricing', fn() => view('manager.pricing.index', $pricingData()))->name('pricing.index');
    Route::get('/pricing/create', fn() => view('manager.pricing.create', $pricingData()))->name('pricing.create');

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
        $data['hostel_id'] = $hostelId;
        $taxIds = $data['tax_ids'] ?? [];
        unset($data['tax_ids']);
        $price = \App\Models\Price::create($data);
        if (!empty($taxIds)) $price->taxes()->sync($taxIds);
        return redirect()->route('manager.pricing.index')->with('success', 'Tarif ajouté.');
    })->name('pricing.store');

    Route::get('/pricing/{price}/edit', function (\App\Models\Price $price) use ($pricingData) {
        abort_unless($price->hostel_id === (int) session('staff_hostel_id'), 403);
        $data = $pricingData();
        $data['price'] = $price->load(['priceable', 'taxes']);
        return view('manager.pricing.edit', $data);
    })->name('pricing.edit');

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
        $taxIds = $data['tax_ids'] ?? [];
        unset($data['tax_ids']);
        $price->update($data);
        $price->taxes()->sync($taxIds);
        return redirect()->route('manager.pricing.index')->with('success', 'Tarif mis à jour.');
    })->name('pricing.update');

    Route::delete('/pricing/{price}', function (\App\Models\Price $price) {
        abort_unless($price->hostel_id === (int) session('staff_hostel_id'), 403);
        $price->taxes()->detach();
        $price->delete();
        return redirect()->route('manager.pricing.index')->with('success', 'Tarif supprimé.');
    })->name('pricing.destroy');

    // ── Beds ─────────────────────────────────────────────────────────────
    Route::get('/beds', function () {
        $hostelId = session('staff_hostel_id');
        $beds  = \App\Models\Bed::whereHas('room', fn($q) => $q->where('hostel_id', $hostelId))->with('room')->get();
        $rooms = \App\Models\Room::where('hostel_id', $hostelId)->where('type', 'dormitory')->get();
        return view('manager.beds.index', compact('beds', 'rooms'));
    })->name('beds.index');

    Route::post('/beds', function (\Illuminate\Http\Request $request) {
        $hostelId = session('staff_hostel_id');
        $data = $request->validate(['name' => 'required|string|max:100', 'room_id' => 'required|exists:rooms,id']);
        $room = \App\Models\Room::where('id', $data['room_id'])->where('hostel_id', $hostelId)->firstOrFail();
        \App\Models\Bed::create(['name' => $data['name'], 'room_id' => $room->id]);
        return redirect()->route('manager.beds.index')->with('success', 'Lit ajouté.');
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

    // ── Taxes ────────────────────────────────────────────────────────────
    Route::get('/taxes', [ManagerTaxController::class, 'index'])->name('taxes.index');
    Route::put('/taxes', [ManagerTaxController::class, 'update'])->name('taxes.update');

    // ── Staff management ─────────────────────────────────────────────────
    Route::get('/staff', function () {
        $hostelId = session('staff_hostel_id');
        $staff = \App\Models\User::whereHas('hostels', fn($q) => $q->where('hostels.id', $hostelId))
            ->with(['hostels' => fn($q) => $q->where('hostels.id', $hostelId)])
            ->get()->map(function ($u) {
                $u->roleInHostel   = $u->hostels->first()?->pivot->role;
                $u->statusInHostel = $u->hostels->first()?->pivot->status;
                return $u;
            });
        return view('manager.staff.index', compact('staff'));
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
        $newUser->hostels()->attach($hostelId, ['role' => $data['role'], 'status' => 'active']);
        return redirect()->route('manager.staff.index')->with('success', 'Membre ajouté.');
    })->name('staff.store');

    Route::delete('/staff/{staffUser}', function (\App\Models\User $staffUser) {
        $staffUser->hostels()->detach(session('staff_hostel_id'));
        return redirect()->route('manager.staff.index')->with('success', 'Membre retiré.');
    })->name('staff.destroy');

    // ── Settings ─────────────────────────────────────────────────────────
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

}); // fin manager

// ═══════════════════════════════════════════════════════
// STAFF ROUTES
// ═══════════════════════════════════════════════════════
Route::prefix('staff')->name('staff.')->middleware('auth:user')->group(function () {

    // ── Dépenses (staff / financial) ─────────────────────────────────────
    Route::post('/expenses/check-password', [StaffExpenseController::class, 'checkPassword'])
        ->name('expenses.check-password');
    Route::resource('expenses', StaffExpenseController::class)->except(['show']);

    // ── Dashboards ───────────────────────────────────────────────────────
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::get('/financial/dashboard', [FinancialDashboardController::class, 'index'])->name('financial.dashboard');
    Route::get('/financial/reports', [FinancialReportController::class, 'index'])->name('financial.reports.index');
    Route::post('/financial/reports/generate', [FinancialReportController::class, 'generate'])->name('financial.reports.generate');
    Route::get('/cash-shifts', [CashShiftController::class, 'index'])->name('cash-shifts.index');
    Route::post('/cash-shifts/open', [CashShiftController::class, 'open'])->name('cash-shifts.open');

    // ── Reservations (staff) ─────────────────────────────────────────────
    Route::get('/reservations',                 [ManagerReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/available-units', [ManagerReservationController::class, 'availableUnits'])->name('reservations.available-units');
    Route::post('/reservations/check-password', [ManagerReservationController::class, 'checkPassword'])->name('reservations.check-password');
    Route::get('/reservations/create',          [ManagerReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations',                [ManagerReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations/{id}/edit',       [ManagerReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/reservations/{id}',            [ManagerReservationController::class, 'update'])->name('reservations.update');
    Route::delete('/reservations/{id}',         [ManagerReservationController::class, 'destroy'])->name('reservations.destroy');

    // ── Payments (staff) ─────────────────────────────────────────────────
    Route::get('/payments/reservation/{reservation}/people', [\App\Http\Controllers\Staff\StaffPaymentController::class, 'people'])->name('payments.people');
    Route::get('/payments',           [\App\Http\Controllers\Staff\StaffPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create',    [\App\Http\Controllers\Staff\StaffPaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments',          [\App\Http\Controllers\Staff\StaffPaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [\App\Http\Controllers\Staff\StaffPaymentController::class, 'show'])->name('payments.show');

}); // fin staff