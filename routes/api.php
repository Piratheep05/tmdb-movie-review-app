<?php

use App\Http\Controllers\Api\MovieApiController;
use App\Http\Controllers\Api\ReviewApiController;
use Illuminate\Support\Facades\Route;

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

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Movie search API
    Route::get('/movies/search', [MovieApiController::class, 'search'])->name('api.movies.search');
    Route::get('/movies/{movieId}', [MovieApiController::class, 'show'])->name('api.movies.show');
});

// Protected API routes (require authentication)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Review API endpoints
    Route::apiResource('reviews', ReviewApiController::class);

    // Movie API with reviews
    Route::get('/movies/{movieId}/reviews', [MovieApiController::class, 'reviews'])->name('api.movies.reviews');
});
