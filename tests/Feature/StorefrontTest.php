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
                ->where('whatsappUrl', 'https://wa.me/628120000000?text='.rawurlencode("Halo Otim Florist, saya ingin memesan Rose Bouquet M (Rp 350.000).\n\nLink produk: ".route('storefront.products.show', $product)))
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
            'google_reviews_url' => 'https://share.google/test-link',
            'google_rating' => 4.8,
            'google_reviews_count' => 50,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Catalog')
                ->where('store.name', 'Florist Indah')
                ->where('store.address', 'Jl. Kenanga No. 5, Bandung')
                ->where('store.hours', '09:00–18:00 WIB')
                ->where('store.phone', '6281234567890')
                ->where('store.google_reviews_url', 'https://share.google/test-link')
                ->where('store.google_rating', 4.8)
                ->where('store.google_reviews_count', 50)
            );
    }

    public function test_public_storefront_renders_open_graph_and_preview_meta_tags(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('property="og:image"', false)
            ->assertSee('property="og:title"', false)
            ->assertSee('name="twitter:card"', false)
            ->assertSee('name="twitter:image"', false)
            ->assertInertia(fn ($page) => $page
                ->where('defaultOgImage', asset('images/og-image.jpg'))
            );
    }

    public function test_not_found_public_pages_redirect_to_homepage(): void
    {
        $this->get('/halaman-yang-tidak-ada')
            ->assertRedirect(route('storefront.home'));

        $this->get('/products/produk-tidak-ditemukan')
            ->assertRedirect(route('storefront.home'));

        $this->get('/categories/kategori-tidak-ditemukan')
            ->assertRedirect(route('storefront.home'));
    }

    public function test_not_found_json_requests_remain_not_found(): void
    {
        $this->getJson('/api/non-existent-endpoint')
            ->assertNotFound();
    }
}
