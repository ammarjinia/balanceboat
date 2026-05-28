<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\{OverviewController, AccountController};
use App\Http\Controllers\Retreat\{RetreatController, RetreatPricingController};
use App\Http\Controllers\Booking\BookingController;

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', OverviewController::class . '@index')->name('dashboard');

    Route::prefix('dashboard')->group(function () {
        // Account
        Route::get('/account', AccountController::class . '@show')->name('account.show');
        Route::patch('/account', AccountController::class . '@update')->name('account.update');

        // Retreats
        Route::resource('retreat', RetreatController::class);
        Route::patch('retreat/{retreat}/publish', RetreatController::class . '@publish')->name('retreat.publish');
        Route::patch('retreat/{retreat}/draft', RetreatController::class . '@draft')->name('retreat.draft');
        Route::post('retreat/{retreat}/duplicate', RetreatController::class . '@duplicate')->name('retreat.duplicate');

        // Retreat Pricing
        Route::get('retreat/{retreat}/pricing', RetreatPricingController::class . '@index')->name('retreat.pricing.index');
        Route::post('retreat/{retreat}/pricing', RetreatPricingController::class . '@store')->name('retreat.pricing.store');

        // Bookings
        Route::resource('booking', BookingController::class)->only(['index', 'show']);
        Route::post('booking/{booking}/confirm', BookingController::class . '@confirm')->name('booking.confirm');
        Route::post('booking/{booking}/cancel', BookingController::class . '@cancel')->name('booking.cancel');
    });

    // Public booking
    Route::post('/booking', BookingController::class . '@store')->name('booking.store');
});

// Public routes for bookings
Route::get('/retreat/{retreat}/book/preview', BookingController::class . '@preview')->name('booking.preview');
Route::post('/retreat/{retreat}/pricing/calculate', RetreatPricingController::class . '@calculatePrice')->name('retreat.pricing.calculate');

