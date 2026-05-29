<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PricingController;
use App\Http\Controllers\Api\AvailabilityController;
use App\Http\Controllers\Api\RetreatController as ApiRetreatController;

Route::middleware('auth:sanctum')->group(function () {
    // Pricing
    Route::post('/pricing/calculate', [PricingController::class, 'calculate']);

    // Availability
    Route::get('/availability/calendar', [AvailabilityController::class, 'calendar']);
    Route::get('/availability/check', [AvailabilityController::class, 'checkAvailability']);

    // Retreat
    Route::get('/retreat/{retreat}/summary', [ApiRetreatController::class, 'summary']);
    Route::get('/retreat/{retreat}/availability', [ApiRetreatController::class, 'availability']);

    // User
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
