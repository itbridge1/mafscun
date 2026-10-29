<?php

use App\Http\Controllers\Api\EventBookingApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ---- Public API routes ----
Route::get('/site-info', [EventBookingApiController::class, 'siteInfo']);

// ---- Admin mobile login ----
Route::post('/admin/login', [EventBookingApiController::class, 'adminLogin']);

// ---- Protected API routes (admin sanctum) ----
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/event-bookings/scan',        [EventBookingApiController::class, 'scan']);
    Route::get('/event-bookings/{token}',      [EventBookingApiController::class, 'show']);
    Route::get('/events/{eventId}/bookings',   [EventBookingApiController::class, 'eventBookings']);
    Route::get('/events',                      [EventBookingApiController::class, 'events']);
    Route::post('/event-booking/present/{token}', [EventBookingApiController::class, 'markParticipantPresent']);
});

