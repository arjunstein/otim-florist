<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function overview(): Response
    {
        $totalProducts = Product::count();
        $productsWithImages = Product::query()->whereNotNull('image_path')->count();

        return Inertia::render('Dashboard/Overview', [
            'stats' => [
                ['label' => 'Total products', 'value' => (string) $totalProducts, 'delta' => 'Available in your catalog', 'up' => true],
                ['label' => 'Categories', 'value' => (string) Category::count(), 'delta' => 'Organize your collection', 'up' => true],
                ['label' => 'On sale', 'value' => (string) Product::query()->whereNotNull('sale_price')->count(), 'delta' => 'Products with active offers', 'up' => true],
                ['label' => 'Image coverage', 'value' => "{$productsWithImages}/{$totalProducts}", 'delta' => 'Products with a photo', 'up' => $productsWithImages === $totalProducts],
            ],
            'recentProducts' => Product::query()
                ->with('category:id,name')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'imageUrl' => $product->image_path ? Storage::disk('public')->url($product->image_path) : null,
                    'categoryName' => $product->category->name,
                    'price' => $product->price,
                    'salePrice' => $product->sale_price,
                ]),
            'categorySummary' => Category::query()
                ->withCount('products')
                ->orderByDesc('products_count')
                ->orderBy('name')
                ->take(5)
                ->get()
                ->map(fn (Category $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'productCount' => $category->products_count,
                ]),
            'mostClickedProducts' => Product::query()
                ->with('category:id,name')
                ->where('click_count', '>', 0)
                ->orderByDesc('click_count')
                ->orderBy('name')
                ->take(5)
                ->get()
                ->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'imageUrl' => $product->image_path ? Storage::disk('public')->url($product->image_path) : null,
                    'categoryName' => $product->category->name,
                    'clickCount' => $product->click_count,
                ]),
        ]);
    }

    public function settings(): Response
    {
        return Inertia::render('Dashboard/Settings', [
            'store' => [
                'name' => 'Otim Florist',
                'phone' => '+62 812-3456-7890',
                'address' => 'Jl. Mawar No. 12, Jakarta',
                'hours' => '08:00–20:00 daily',
            ],
        ]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:255'],
            'hours' => ['required', 'string', 'max:100'],
        ]);

        return back()->with('success', 'Store settings saved (dummy, nothing persisted).');
    }
}
