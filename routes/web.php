<?php

// routes/web.php
use App\Http\Controllers\{ItemController,LoanController};
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('loans.index'));

// Route::middleware(['web','auth'])->group(function(){
    Route::get('/items', [ItemController::class,'index'])->name('items.index');
    Route::post('/items', [ItemController::class,'store'])->name('items.store');
    Route::get('/items/search', [ItemController::class,'search'])->name('items.search'); // JSON

    // Loans
    Route::get('/loans', [LoanController::class,'index'])->name('loans.index');
    Route::get('/loans/create', [LoanController::class,'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class,'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class,'show'])->name('loans.show');

    // Returns
    Route::get('/loans/{loan}/return', [LoanController::class,'returnForm'])->name('loans.return.form');
    Route::post('/loans/{loan}/return', [LoanController::class,'processReturn'])->name('loans.return.process');
// });

// jika belum pakai auth, untuk uji cepat: hapus middleware dan jalankan langsung.

