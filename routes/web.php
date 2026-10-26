
<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('pos.create');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    // Point of Sale
    Route::get('/pos', [TransactionController::class, 'create'])
        ->name('pos.create');

    Route::post('/pos', [TransactionController::class, 'store'])
        ->name('transactions.store');

    // Riwayat transaksi
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    // Khusus admin
    Route::middleware('role:admin')->group(function () {
        // Kategori
        Route::resource('categories', CategoryController::class)
            ->except('show');

        // Produk
        Route::resource('products', ProductController::class)
            ->except(['show', 'destroy']);

        // Impor produk dari CSV
        Route::post('/products/import', [ProductImportController::class, 'store'])
            ->name('products.import');

        // Laporan penjualan
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        // Ekspor CSV
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])
            ->name('reports.export.csv');

        // Ekspor Excel
        Route::get('/reports/export/xlsx', [ReportController::class, 'exportExcel'])
            ->name('reports.export.xlsx');

        // Ekspor PDF
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])
            ->name('reports.export.pdf');
    });
});
