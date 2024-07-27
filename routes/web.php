<?php

use App\Livewire\Bungameja;
use App\Livewire\Bungapapan;
use App\Livewire\Bungasalib;
use App\Livewire\Bungastanding;
use App\Livewire\Handbouquet;
use App\Livewire\Landingpage;
use App\Livewire\ProdukDetail;
use App\Livewire\Tentang;
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
// Landing page
Route::get('/', Landingpage::class)->name('homepage');

// Detail produk
Route::get('/produk/{slug}/{id}', ProdukDetail::class)->name('product.detail');

// Tentang kami
Route::get('/tentang-kami', Tentang::class)->name('tentang');

// category navbar
Route::get('/bunga-papan', Bungapapan::class)->name('bungapapan');
Route::get('/bunga-standing', Bungastanding::class)->name('bungastanding');
Route::get('/bunga-buket', Handbouquet::class)->name('buket');
Route::get('/bunga-meja', Bungameja::class)->name('bungameja');
Route::get('/bunga-salib', Bungasalib::class)->name('bungasalib');


// Handle unexpect redirect
Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/admin/logout', function () {
    return redirect(route('filament.admin.auth.login'));
});
