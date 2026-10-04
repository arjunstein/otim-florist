<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\UpdatePasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StorefrontController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/', [StorefrontController::class, 'index'])->name('storefront.home');
Route::get('/tentang', [StorefrontController::class, 'about'])->name('storefront.about');
Route::get('/kontak', [StorefrontController::class, 'contact'])->name('storefront.contact');
Route::get('/categories/{category:slug}', [StorefrontController::class, 'showCategory'])->name('storefront.categories.show');
Route::get('/products/{product:slug}', [StorefrontController::class, 'showProduct'])->name('storefront.products.show');

// Legacy URLs (masih terindeks Google) — wajib balas 301.
Route::redirect('/tentang-kami', '/tentang', 301);
Route::get('/kategori/{slug}', fn (string $slug) => redirect('/categories/'.$slug, 301));
Route::get('/produk/{kategori}/{slug}/{id}', function (string $kategori, string $slug, string $id) {
    // 1) id masih ada -> slug terkini; 2) slug lama masih ada; 3) petakan ke kategori terkini; 4) fallback beranda.
    $product = Product::find($id) ?? Product::where('slug', $slug)->first();
    if ($product) {
        return redirect('/products/'.$product->slug, 301);
    }
    $kat = Category::where('slug', $kategori)->first()
        ?? Category::where('slug', implode('-', array_slice(explode('-', $kategori), 0, 2)))->first();

    return redirect($kat ? '/categories/'.$kat->slug : '/', 301);
});

$loginPath = trim((string) config('auth.login_path', 'management-portal'), '/');
$loginPath = $loginPath !== '' ? $loginPath : 'management-portal';

Route::middleware('guest')->group(function () use ($loginPath) {
    Route::get('/'.$loginPath, [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/'.$loginPath, [AuthenticatedSessionController::class, 'store'])->middleware('throttle:login');
});

Route::redirect('/dashboard', '/admin/dashboard');

Route::middleware(['auth', 'auth.session'])->prefix('admin')->group(function () {
    Route::redirect('/', '/admin/dashboard');
    Route::get('/dashboard', [DashboardController::class, 'overview'])->name('dashboard.overview');
    Route::get('/settings', [DashboardController::class, 'settings'])->name('dashboard.settings');
    Route::put('/settings', [DashboardController::class, 'updateSettings']);
    Route::put('/settings/password', UpdatePasswordController::class)->name('password.update');

    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::resource('products', ProductController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
