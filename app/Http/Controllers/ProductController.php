<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
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
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                    ],
                    'price' => $product->price,
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

    public function store(ProductRequest $request): RedirectResponse
    {
        Product::create($request->validated());

        return to_route('products.index')->with('success', 'Product created.');
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($request->validated());

        return to_route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return to_route('products.index')->with('success', 'Product deleted.');
    }
}
