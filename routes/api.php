<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\FavoriteController;

// Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Récupération de l'utilisateur authentifié
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes protégées pour les itinéraires
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/itineraries', [ItineraryController::class, 'store']);
    Route::put('/itineraries/{itinerary}', [ItineraryController::class, 'update']);
    Route::delete('/itineraries/{itinerary}', [ItineraryController::class, 'destroy']);
    Route::post('/itineraries/{itineraryId}/destinations', [ItineraryController::class, 'addDestinations']);

    // Routes des favoris
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{itineraryId}', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{itineraryId}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

// Routes publiques pour les itinéraires (consultation)
Route::get('/itineraries', [ItineraryController::class, 'index']); // Liste des itinéraires
Route::get('/itineraries/search', [ItineraryController::class, 'searchItineraries']); // Recherche
Route::get('/itineraries/{itinerary}', [ItineraryController::class, 'show']); // Détails

