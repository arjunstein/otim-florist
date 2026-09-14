<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'overview'])->name('dashboard.overview');
Route::get('/products', [DashboardController::class, 'products'])->name('dashboard.products');
Route::get('/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');
Route::put('/settings', [DashboardController::class, 'updateSettings']);
