<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome')->middleware('guest')->name('welcome');
Route::get('/dashboard', DashboardController::class)->name('dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Kasir POS (increment 2: routing & controller)
Route::get('/pos', [TransactionController::class, 'create'])->name('pos.create');
Route::post('/pos', [TransactionController::class, 'store'])->name('transactions.store');
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');

Route::resource('categories', CategoryController::class)->except('show');
Route::resource('products', ProductController::class)->except(['show', 'destroy']);
