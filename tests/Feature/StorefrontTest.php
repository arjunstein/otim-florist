<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_renders_categories_and_products(): void
    {
        $category = Category::create(['name' => 'Bouquet']);
        Product::create([
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Catalog')
                ->where('categories.0.slug', 'bouquet')
                ->where('products.0.slug', 'rose-bouquet-m')
            );
    }

    public function test_public_product_and_category_pages_resolve_by_slug(): void
    {
        $category = Category::create(['name' => 'Bouquet']);
        $product = Product::create([
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
        ]);

        $this->get("/products/{$product->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Product')
                ->where('product.slug', $product->slug)
            );

        $this->get("/categories/{$category->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Category')
                ->where('category.slug', $category->slug)
                ->where('products.0.slug', $product->slug)
            );
    }
}
