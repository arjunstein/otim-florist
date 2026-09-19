<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\UpdatePasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StorefrontController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'index'])->name('storefront.home');
Route::get('/categories/{category:slug}', [StorefrontController::class, 'showCategory'])->name('storefront.categories.show');
Route::get('/products/{product:slug}', [StorefrontController::class, 'showProduct'])->name('storefront.products.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login');
});

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'overview'])->name('dashboard.overview');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');
    Route::put('/settings', [DashboardController::class, 'updateSettings']);
    Route::put('/settings/password', UpdatePasswordController::class)->name('password.update');

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('products', ProductController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
