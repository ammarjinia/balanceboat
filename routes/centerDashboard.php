<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\OverviewController;
use App\Http\Controllers\Dashboard\AccountController;
use App\Http\Controllers\Retreat\RetreatController;
use App\Http\Controllers\Retreat\RetreatPricingController;
use App\Http\Controllers\Booking\BookingController;

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [OverviewController::class, 'index'])->name('dashboard.index');

    // Account
    Route::get('/account', [AccountController::class, 'show'])->name('dashboard.account.show');
    Route::patch('/account', [AccountController::class, 'update'])->name('dashboard.account.update');

    // Retreats
    Route::resource('/retreat', RetreatController::class);
    Route::patch('/retreat/{retreat}/publish', [RetreatController::class, 'publish'])->name('retreat.publish');
    Route::patch('/retreat/{retreat}/draft', [RetreatController::class, 'draft'])->name('retreat.draft');
    Route::post('/retreat/{retreat}/duplicate', [RetreatController::class, 'duplicate'])->name('retreat.duplicate');

    // Retreat Pricing
    Route::get('/retreat/{retreat}/pricing', [RetreatPricingController::class, 'index'])->name('retreat.pricing');
    Route::post('/retreat/{retreat}/pricing', [RetreatPricingController::class, 'store'])->name('retreat.pricing.store');
    Route::get('/retreat/{retreat}/pricing/calculate', [RetreatPricingController::class, 'calculatePrice'])->name('retreat.pricing.calculate');

    // Bookings
    Route::resource('/booking', BookingController::class, ['only' => ['index', 'show']]);
    Route::post('/booking/{booking}/confirm', [BookingController::class, 'confirm'])->name('booking.confirm');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');
});

// Public Routes
Route::get('/retreat/{experience}/book/preview', [BookingController::class, 'preview'])->name('booking.preview');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
