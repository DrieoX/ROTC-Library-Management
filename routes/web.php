<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', function () {
    return view('welcome');
});
Route::get('librarian/', [BookController::class, 'index'])->name('librarian.welcome');

// Dashboard route for regular users (requires authentication)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Librarian-specific dashboard route
Route::get('/librarian/dashboard', [DashboardController::class, 'librarianIndex'])
    ->middleware(['auth', 'verified']) // Ensure only librarians can access this route
    ->name('librarian.dashboard');

// Profile routes for authenticated users
Route::middleware(['auth', 'no-cache'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Authentication routes
require __DIR__.'/auth.php';

// Login routes with guest middleware
Route::get('/login', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

// Student registration routes
Route::get('/register/student', [RegisteredUserController::class, 'createStudent'])->name('register-student');
Route::post('/register/student', [RegisteredUserController::class, 'storeStudent'])->name('register-student.store');

// Librarian registration routes
Route::get('/register/librarian', [RegisteredUserController::class, 'createLibrarian'])->name('register-librarian');
Route::post('/register/librarian', [RegisteredUserController::class, 'storeLibrarian'])->name('register-librarian.store');

// Achievement routes for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
});

// Book search route
Route::post('/search-books', [BookController::class, 'searchBooks'])->name('search-books');
