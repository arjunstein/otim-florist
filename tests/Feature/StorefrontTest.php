<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_catalog_renders_navigation_categories_and_products(): void
    {
        $category = Category::create(['name' => 'Bouquet']);
        Product::create([
            'name' => 'Rose Bouquet M',
            'description' => 'A soft pink rose arrangement.',
            'image_path' => 'products/rose.jpg',
            'category_id' => $category->id,
            'price' => 350000,
            'sale_price' => 300000,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Catalog')
                ->where('navigationCategories.0.slug', 'bouquet')
                ->where('products.0.slug', 'rose-bouquet-m')
                ->where('products.0.description', 'A soft pink rose arrangement.')
                ->where('products.0.salePrice', 300000)
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
                ->where('navigationCategories.0.slug', 'bouquet')
                ->where('product.slug', $product->slug)
            );

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'click_count' => 1,
        ]);

        $this->get("/categories/{$category->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Category')
                ->where('category.slug', $category->slug)
                ->where('navigationCategories.0.slug', 'bouquet')
                ->where('products.0.slug', $product->slug)
            );
    }
}
