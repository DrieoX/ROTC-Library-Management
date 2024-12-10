<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;


// Home route
Route::get('/', [BookController::class, 'welcome'])->name('welcome');



Route::middleware(['auth', 'verified'])->group(function () {
    // Librarian routes
    Route::get('/librarian', [BookController::class, 'index'])->name('librarian.welcome');
    Route::get('/librarian/requests/list', [RequestController::class, 'listRequests'])->name('requests.list');
    Route::get('librarian/requests/stats', [RequestController::class, 'stats'])->name('requests.stats');

    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    Route::get('/librarian/dashboard', [DashboardController::class, 'librarianIndex'])
    ->middleware(['auth', 'verified'])
    ->name('librarian.dashboard');

    // Achievements
Route::middleware(['auth'])->group(function () {
    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
});

// Routes for BookController
Route::get('/books/create', [BookController::class, 'create'])->name('books.create'); // Create book
Route::post('/books', [BookController::class, 'store'])->name('books.store'); // Store book
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit'); // Edit book
Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update'); // Update book
// routes/web.php
Route::get('books/{id}/destroy', [BookController::class, 'confirmDelete'])->name('books.confirmDelete');
Route::delete('books/{id}/destroy', [BookController::class, 'destroy'])->name('books.destroy');


// Routes for RequestController
Route::post('/requests/{bookId}', [RequestController::class, 'store'])->name('requests.store');
Route::get('/requests/{requestId}/approve', [RequestController::class, 'approve'])->name('requests.approve');
Route::get('/requests/{requestId}/deny', [RequestController::class, 'deny'])->name('requests.deny');
Route::post('/requests/{transaction}/return', [RequestController::class, 'return'])->name('requests.return');

Route::get('/fines', [FineController::class, 'index'])->name('fines.index');
Route::post('/fines/pay/{id}', [FineController::class, 'payFine'])->name('fines.pay');
Route::get('/transactions/{transaction}/confirm', [FineController::class, 'confirmPayment'])->name('transactions.confirm');
Route::get('/transactions', [FineController::class, 'index'])->name('transactions.index');

});


// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [DashboardController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [DashboardController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [DashboardController::class, 'destroy'])->name('profile.destroy');
    Route::match(['put', 'patch'], '/password', [DashboardController::class, 'updatePassword'])->name('password.update');

});

// Authentication routes
require __DIR__.'/auth.php';

// Login routes
Route::get('/login', [LoginController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('guest')->name('login.store');

// Student registration routes
Route::get('/register/student', [RegisteredUserController::class, 'createStudent'])->name('register-student');
Route::post('/register/student', [RegisteredUserController::class, 'storeStudent'])->name('register-student.store');

// Librarian registration routes
Route::get('/register/librarian', [RegisteredUserController::class, 'createLibrarian'])->name('register-librarian');
Route::post('/register/librarian', [RegisteredUserController::class, 'storeLibrarian'])->name('register-librarian.store');

// Routes for ListController
Route::get('/books/list', [ListController::class, 'index'])->name('books.list');
Route::get('/books/search', [ListController::class, 'search'])->name('books.search');

