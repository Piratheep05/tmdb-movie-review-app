<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieDetailsController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('welcome');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Movie routes (with rate limiting)
    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/movies/search', [MovieController::class, 'search'])->name('movies.search');
        Route::get('/movies/{movieId}', [MovieDetailsController::class, 'show'])->name('movies.show');
    });

    // Review routes (custom create route must come before resource)
    Route::get('/reviews/create/{movieId}', [ReviewController::class, 'create'])->name('reviews.create');
    Route::resource('reviews', ReviewController::class)->except(['show', 'create']);
});
