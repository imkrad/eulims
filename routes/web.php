<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['2fa','auth','verified'])->group(function () {
    Route::resource('/profile', App\Http\Controllers\Auth\ProfileController::class);
});

Route::middleware(['2fa','auth','verified','is_active','menu'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index']);
    Route::get('/search', [App\Http\Controllers\DashboardController::class, 'search']);
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/executive', App\Http\Controllers\ExecutiveController::class);
    Route::resource('/references', App\Http\Controllers\ReferenceController::class);
    Route::resource('/management', App\Http\Controllers\ManagementController::class);

    Route::resource('/customers', App\Http\Controllers\Operation\CustomerController::class);
    Route::resource('/quotations', App\Http\Controllers\Operation\QuotationController::class);
    Route::resource('/services', App\Http\Controllers\Operation\ServiceController::class);
    Route::resource('/tsrs', App\Http\Controllers\Operation\TsrController::class);
});

require __DIR__.'/auth.php';
