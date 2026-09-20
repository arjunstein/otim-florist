<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateStoreSettingsRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Http\RedirectResponse;
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
        $store = StoreSetting::current();

        return Inertia::render('Dashboard/Settings', [
            'store' => [
                'name' => $store->name,
                'phone' => $store->phone,
                'address' => $store->address,
                'hours' => $store->hours,
            ],
        ]);
    }

    public function updateSettings(UpdateStoreSettingsRequest $request): RedirectResponse
    {
        StoreSetting::current()->update($request->validated());

        return back()->with('success', 'Store settings saved.');
    }
}
