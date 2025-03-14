<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItineraryController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/itineraries', [ItineraryController::class, 'store']);
    Route::put('/itineraries/{itinerary}', [ItineraryController::class, 'update']);
    Route::delete('/itineraries/{itinerary}', [ItineraryController::class, 'destroy']);
});

Route::get('/itineraries', [ItineraryController::class, 'index']);
Route::get('/itineraries/{itinerary}', [ItineraryController::class, 'show']);
Route::post('/itineraries/{itineraryId}/destinations', [ItineraryController::class, 'addDestinations']);
Route::get('/itineraries', [ItineraryController::class, 'getAllItineraries']);
Route::get('/itineraries/search', [ItineraryController::class, 'searchItineraries']);
