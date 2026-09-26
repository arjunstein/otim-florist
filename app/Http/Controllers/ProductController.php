<?php

namespace App\Http\Controllers;

use App\Actions\GenerateProductName;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): Response
    {
        $products = Product::query()
            ->with('category:id,name')
            ->when($request->search(), fn ($query, string $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->categoryId(), fn ($query, int $categoryId) => $query->where('category_id', $categoryId))
            ->orderBy('name')
            ->paginate($request->perPage())
            ->withQueryString();

        return Inertia::render('Dashboard/Products', [
            'categories' => Category::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Category $category) => [
                    'id' => $category->id,
                    'name' => $category->name,
                ]),
            'products' => [
                'data' => $products->getCollection()->map(fn (Product $product) => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'imageUrl' => $product->image_path ? Storage::disk('public')->url($product->image_path) : null,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ],
                    'price' => $product->price,
                    'salePrice' => $product->sale_price,
                ]),
                'pagination' => [
                    'currentPage' => $products->currentPage(),
                    'lastPage' => $products->lastPage(),
                    'perPage' => $products->perPage(),
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                    'nextPageUrl' => $products->nextPageUrl(),
                    'prevPageUrl' => $products->previousPageUrl(),
                ],
            ],
            'filters' => [
                'search' => $request->search(),
                'categoryId' => $request->categoryId(),
            ],
        ]);
    }

    public function store(ProductRequest $request, GenerateProductName $generateProductName): RedirectResponse
    {
        $attributes = $this->productAttributes($request);
        $category = Category::findOrFail($attributes['category_id']);
        $attributes['name'] = $generateProductName($attributes['name'], $category);

        Product::create($attributes);

        return to_route('products.index')->with('success', 'Product created.');
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $attributes = $this->productAttributes($request);
        $previousImagePath = isset($attributes['image_path']) ? $product->image_path : null;

        $product->update($attributes);

        if ($previousImagePath) {
            Storage::disk('public')->delete($previousImagePath);
        }

        return to_route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return to_route('products.index')->with('success', 'Product deleted.');
    }

    private function productAttributes(ProductRequest $request): array
    {
        $attributes = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $attributes['image_path'] = $request->file('image')->store('products', 'public');
        }

        return $attributes;
    }
}
