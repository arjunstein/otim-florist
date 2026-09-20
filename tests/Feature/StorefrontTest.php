<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
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
                ->where('canonicalUrl', route('storefront.home'))
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
        StoreSetting::create([
            'name' => 'Otim Florist',
            'phone' => '628120000000',
            'address' => 'Jakarta',
            'hours' => '08:00–20:00',
        ]);

        $this->get("/products/{$product->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Product')
                ->where('canonicalUrl', route('storefront.products.show', $product))
                ->where('navigationCategories.0.slug', 'bouquet')
                ->where('product.slug', $product->slug)
                ->where('whatsappUrl', 'https://wa.me/628120000000?text=Halo%20Otim%20Florist%2C%20saya%20ingin%20memesan%20Rose%20Bouquet%20M%20%28Rp%20350.000%29.')
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

    public function test_sitemap_lists_public_catalog_urls_and_robots_references_it(): void
    {
        $category = Category::create(['name' => 'Bouquet']);
        $product = Product::create([
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
        ]);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee(route('storefront.home'), false)
            ->assertSee(route('storefront.categories.show', $category), false)
            ->assertSee(route('storefront.products.show', $product), false);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap: '.route('sitemap'), false);
    }

    public function test_public_storefront_receives_configured_store_settings(): void
    {
        StoreSetting::query()->updateOrCreate(['id' => 1], [
            'name' => 'Florist Indah',
            'phone' => '6281234567890',
            'address' => 'Jl. Kenanga No. 5, Bandung',
            'hours' => '09:00–18:00 WIB',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Catalog')
                ->where('store.name', 'Florist Indah')
                ->where('store.address', 'Jl. Kenanga No. 5, Bandung')
                ->where('store.hours', '09:00–18:00 WIB')
                ->where('store.phone', '6281234567890')
            );
    }
}
