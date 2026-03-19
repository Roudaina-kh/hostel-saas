<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\OwnerRegisterController;
use App\Http\Controllers\Auth\OwnerLoginController;
use App\Http\Controllers\HostelController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\TentSpaceController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\TaxController;

// Auth
Route::get('/register', [OwnerRegisterController::class, 'showForm'])->name('register');
Route::post('/register', [OwnerRegisterController::class, 'register']);
Route::get('/login', [OwnerLoginController::class, 'showForm'])->name('login');
Route::post('/login', [OwnerLoginController::class, 'login']);
Route::post('/logout', [OwnerLoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    // Onboarding — sans hostel.selected
    Route::get('/onboarding', [HostelController::class, 'onboarding'])->name('onboarding.create');
    Route::post('/onboarding', [HostelController::class, 'storeFirst'])->name('onboarding.store');

    // Hostel switcher — sans hostel.selected
    Route::post('/hostel/switch/{hostel}', [HostelController::class, 'switchHostel'])->name('hostel.switch');

    // Tout ce qui nécessite un hostel actif
    Route::middleware(['hostel.selected'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Hostels
        Route::resource('hostels', HostelController::class)->except(['show']);

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
    });
});