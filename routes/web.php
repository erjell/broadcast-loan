<?php

use App\Http\Controllers\{ItemController, LoanController, CategoryController, ProfileController, ReportController};
use Illuminate\Support\Facades\Route;
use App\Models\{Loan, LoanItem, Item};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

$renderDashboard = function () {
    $totalLoans = Loan::count();

    // Total barang yang belum dikembalikan (berdasarkan baris loan_items yang belum memiliki return_condition)
    $totalUnreturned = LoanItem::whereNull('return_condition')
        ->whereHas('loan', function ($q) {
            $q->whereIn('status', ['dipinjam', 'sebagian_kembali']);
        })
        ->count();

    $totalItems = Item::count();
    $totalDamagedItems = Item::whereIn('condition', ['rusak_ringan','rusak_berat'])->count();

    // Transaksi aktif dan selesai
    $totalActiveLoans = Loan::whereIn('status', ['dipinjam','sebagian_kembali'])->count();
    $totalCompletedLoans = Loan::where('status','selesai')->count();

    // Top 5 barang paling sering dipinjam (berdasarkan frekuensi kemunculan di loan_items)
    $topItems = LoanItem::selectRaw('item_id, COUNT(*) as total')
        ->groupBy('item_id')
        ->orderByDesc('total')
        ->with('item:id,name,code')
        ->take(5)
        ->get();

    // Top 20 barang paling sering rusak (return_condition rusak_*), paginate 5 per halaman
    $topDamagedAll = LoanItem::selectRaw('item_id, COUNT(*) as total_damages')
        ->whereIn('return_condition', ['rusak_ringan', 'rusak_berat'])
        ->groupBy('item_id')
        ->orderByDesc('total_damages')
        ->with('item:id,name,code')
        ->take(20)
        ->get();

    $perPage = 5;
    $pageName = 'rusak_page';
    $currentPage = Paginator::resolveCurrentPage($pageName);
    $itemsForCurrentPage = $topDamagedAll->slice(($currentPage - 1) * $perPage, $perPage)->values();
    $topDamaged = new LengthAwarePaginator(
        $itemsForCurrentPage,
        $topDamagedAll->count(),
        $perPage,
        $currentPage,
        [
            'path' => request()->url(),
            'pageName' => $pageName,
            'query' => request()->query(),
        ]
    );

    return view('dashboard', compact(
        'totalLoans',
        'totalUnreturned',
        'totalItems',
        'totalDamagedItems',
        'totalActiveLoans',
        'totalCompletedLoans',
        'topItems',
        'topDamaged'
    ));
};

Route::get('/', $renderDashboard)->name('dashboard');
Route::get('/dashboard', fn () => redirect()->route('dashboard'));

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Items
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::get('/items/export', [ItemController::class, 'export'])->name('items.export');
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
    Route::get('/loans/export', [LoanController::class, 'export'])->name('loans.export');
    Route::get('/loans/create', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::get('/loans/{loan}/receipt', [LoanController::class, 'receiptPdf'])->name('loans.receipt');

    // Returns
    Route::get('/loans/{loan}/return', [LoanController::class, 'returnForm'])->name('loans.return.form');
    Route::post('/loans/{loan}/return', [LoanController::class, 'processReturn'])->name('loans.return.process');

    // Reports
    Route::get('/reports/damages', [ReportController::class, 'damageLogs'])->name('reports.damages');
    Route::get('/reports/damages/export', [ReportController::class, 'exportDamageLogs'])->name('reports.damages.export');
});

require __DIR__.'/auth.php';


