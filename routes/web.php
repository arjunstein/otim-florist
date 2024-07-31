<?php

use App\Livewire\Bungameja;
use App\Livewire\Bungapapan;
use App\Livewire\Bungapapancongratulation;
use App\Livewire\Bungapapandukacita;
use App\Livewire\Bungapapanhappywedding;
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
Route::get('/kategori/bunga-papan', Bungapapan::class)->name('bungapapan');
Route::get('/kategori/bunga-papan-congratulations', Bungapapancongratulation::class)->name('bungapapan-congratulation');
Route::get('/kategori/bunga-papan-happy-wedding', Bungapapanhappywedding::class)->name('bungapapan-wedding');
Route::get('/kategori/bunga-papan-dukacita', Bungapapandukacita::class)->name('bungapapan-dukacita');
Route::get('/kategori/bunga-standing', Bungastanding::class)->name('bungastanding');
Route::get('/kategori/hand-bouquet', Handbouquet::class)->name('buket');
Route::get('/kategori/bunga-meja', Bungameja::class)->name('bungameja');
Route::get('/kategori/bunga-salib', Bungasalib::class)->name('bungasalib');

// Handle unexpect redirect
Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/admin/logout', function () {
    return redirect(route('filament.admin.auth.login'));
});
