<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'overview'])->name('dashboard.overview');
Route::get('/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');
Route::put('/settings', [DashboardController::class, 'updateSettings']);

Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
Route::resource('products', ProductController::class)->only(['index', 'store', 'update', 'destroy']);
