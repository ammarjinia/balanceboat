<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Pricing API
    Route::post('/pricing/calculate', 'App\Http\Controllers\Api\PricingController@calculate');

    // Availability API
    Route::get('/availability/calendar', 'App\Http\Controllers\Api\AvailabilityController@calendar');

    // Retreat API
    Route::get('/retreat/{retreat}/summary', 'App\Http\Controllers\Api\RetreatController@summary');
    Route::get('/retreat/{retreat}/availability', 'App\Http\Controllers\Api\RetreatController@availability');
});
