<?php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::get('/search',       [SearchApiController::class, 'search']);
    Route::get('/regions',      [SearchApiController::class, 'regions']);
    Route::get('/hostels/{id}', [SearchApiController::class, 'hostel']);
    Route::get('/availability', [SearchApiController::class, 'availability']);
});