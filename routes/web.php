<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductExcelImportController;
use App\Http\Controllers\ProductImportController;
use App\Http\Controllers\QuickCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('pos.create');
});

Route::get('/dashboard', DashboardController::class)
    ->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'store'])
        ->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

    // Kasir POS
    Route::get('/pos', [TransactionController::class, 'create'])
        ->name('pos.create');

    Route::post('/pos', [TransactionController::class, 'store'])
        ->name('transactions.store');

    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('transactions.show');

    // Grup khusus Admin
    Route::middleware('role:admin')->group(function () {
        Route::resource('categories', CategoryController::class)
            ->except('show');

        Route::resource('products', ProductController::class)
            ->except(['show', 'destroy']);

        // Impor produk dari CSV (Queue - Langkah 8)
        Route::post('/products/import', [ProductImportController::class, 'store'])
            ->name('products.import');

        // Impor produk menggunakan Laravel Excel (Langkah 10 Nadia)
        Route::post('/products/import/excel', [ProductExcelImportController::class, 'store'])
            ->name('products.import.excel');

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

        // Import produk CSV dengan tabel pencatatan error (Langkah 10 Sharon)
        Route::get('/import', [ImportController::class, 'index'])
            ->name('imports.index');

        Route::post('/import', [ImportController::class, 'store'])
            ->name('imports.store');

        // Kategori cepat AJAX (Langkah 11 Yosep)
        Route::get('/quick-categories', [QuickCategoryController::class, 'index'])
            ->name('quick-categories.index');

        Route::post('/quick-categories', [QuickCategoryController::class, 'store'])
            ->name('quick-categories.store');
    });
});
