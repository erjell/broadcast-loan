<?php

use App\Http\Controllers\{ItemController, LoanController, CategoryController, ProfileController, ReportController};
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('loans.index'));

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Items
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
    Route::get('/items/search', [ItemController::class, 'search'])->name('items.search'); // JSON
    Route::get('/items/lookup', [ItemController::class, 'lookup'])->name('items.lookup'); // JSON exact by code/serial
    Route::get('/items/code', [ItemController::class, 'code'])->name('items.code'); // JSON
    Route::get('/items/print', [ItemController::class, 'print'])->name('items.print');

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Loans
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');

    // Returns
    Route::get('/loans/{loan}/return', [LoanController::class, 'returnForm'])->name('loans.return.form');
    Route::post('/loans/{loan}/return', [LoanController::class, 'processReturn'])->name('loans.return.process');

    // Reports
    Route::get('/reports/damages', [ReportController::class, 'damageLogs'])->name('reports.damages');
});

require __DIR__.'/auth.php';
