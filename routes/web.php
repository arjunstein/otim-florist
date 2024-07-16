<?php

use App\Livewire\Landingpage;
use App\Livewire\ProdukDetail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Landingpage::class);
Route::get('/produk/{id}/{slug}', ProdukDetail::class)->name('product.detail');

Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/admin/logout', function () {
    return redirect(route('filament.admin.auth.login'));
});
