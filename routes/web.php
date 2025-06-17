<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ComplaintController;

// Public routes
Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

// Auth routes
require __DIR__.'/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [ComplaintController::class, 'dashboard'])->name('dashboard');
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'add'])->name('complaints.add');
});

// Settings routes
require __DIR__.'/settings.php';
