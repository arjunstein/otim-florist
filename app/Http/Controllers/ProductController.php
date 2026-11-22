<?php

namespace App\Http\Controllers;

use App\Actions\GenerateProductName;
use App\Actions\GenerateProductThumbnail;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;
use Throwable;

class ProductController extends Controller
{
    public function index(ProductIndexRequest $request): Response
    {
        $products = Product::query()
            ->with('category:id,name')
            ->when($request->search(), function ($query, string $search) {
                $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search);

                return $query->where('name', 'like', "%{$escaped}%");
            })
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
                    'thumbnailUrl' => $product->thumbnailUrl(),
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
        $category = Category::findOrFail($request->validated('category_id'));
        $name = $generateProductName($request->validated('name'), $category);
        $attributes = $this->productAttributes($request);
        $attributes['name'] = $name;

        try {
            Product::create($attributes);
        } catch (Throwable $exception) {
            $this->deleteImage($attributes['image_path'] ?? null);
            $this->deleteImage($attributes['thumbnail_path'] ?? null);
            throw $exception;
        }

        return to_route('products.index')->with('success', 'Product created.');
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $attributes = $this->productAttributes($request);
        $previousImagePath = $product->image_path;
        $previousThumbnailPath = $product->thumbnail_path;

        try {
            $product->update($attributes);
        } catch (Throwable $exception) {
            $this->deleteImage($attributes['image_path'] ?? null);
            $this->deleteImage($attributes['thumbnail_path'] ?? null);
            throw $exception;
        }

        if (isset($attributes['image_path'])) {
            $this->deleteImage($previousImagePath);
            $this->deleteImage($previousThumbnailPath);
        }

        return to_route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $imagePath = $product->image_path;
        $thumbnailPath = $product->thumbnail_path;
        $product->delete();
        $this->deleteImage($imagePath);
        $this->deleteImage($thumbnailPath);

        return to_route('products.index')->with('success', 'Product deleted.');
    }

    private function productAttributes(ProductRequest $request): array
    {
        $attributes = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $attributes['image_path'] = $request->file('image')->store('products', 'public');
            if ($attributes['image_path'] === false) {
                throw new RuntimeException('Unable to store product image.');
            }

            $attributes['thumbnail_path'] = null;
            try {
                $attributes['thumbnail_path'] = app(GenerateProductThumbnail::class)($attributes['image_path']);
            } catch (Throwable $exception) {
                report($exception);
            }
        }

        return $attributes;
    }

    private function deleteImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        try {
            if (! Storage::disk('public')->delete($path)) {
                Log::warning('Could not delete product image', ['path' => $path]);
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }
}
