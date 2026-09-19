<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class StorefrontController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Storefront/Catalog', [
            'navigationCategories' => $this->navigationCategories(),
            'products' => Product::query()
                ->with('category:id,name,slug')
                ->orderBy('name')
                ->get()
                ->map(fn (Product $product) => $this->productData($product)),
        ]);
    }

    public function showCategory(Category $category): Response
    {
        $category->loadCount('products');
        $products = $category->products()
            ->with('category:id,name,slug')
            ->orderBy('name')
            ->get();

        return Inertia::render('Storefront/Category', [
            'navigationCategories' => $this->navigationCategories(),
            'category' => $this->categoryData($category),
            'products' => $products->map(fn (Product $product) => $this->productData($product)),
        ]);
    }

    public function showProduct(Product $product): Response
    {
        $product->load('category:id,name,slug');

        return Inertia::render('Storefront/Product', [
            'navigationCategories' => $this->navigationCategories(),
            'product' => $this->productData($product),
        ]);
    }

    /**
     * @return array<int, array{name: string, slug: string}>
     */
    private function navigationCategories(): array
    {
        return Category::query()
            ->orderBy('name')
            ->get(['name', 'slug'])
            ->map(fn (Category $category) => [
                'name' => $category->name,
                'slug' => $category->slug,
            ])
            ->all();
    }

    /**
     * @return array{id: int, name: string, slug: string, productCount: int}
     */
    private function categoryData(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'productCount' => $category->products_count ?? 0,
        ];
    }

    /**
     * @return array{id: int, name: string, slug: string, price: int, category: array{id: int, name: string, slug: string}}
     */
    private function productData(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->name,
                'slug' => $product->category->slug,
            ],
        ];
    }
}
