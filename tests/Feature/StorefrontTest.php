<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StoreSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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
            ->assertSee('<priority>1.0</priority>', false)
            ->assertSee('<changefreq>daily</changefreq>', false)
            ->assertSee(route('storefront.categories.show', $category), false)
            ->assertSee('<priority>0.8</priority>', false)
            ->assertSee(route('storefront.products.show', $product), false)
            ->assertSee('<priority>0.6</priority>', false)
            ->assertSee(route('storefront.about'), false)
            ->assertSee(route('storefront.contact'), false);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('Sitemap: '.route('sitemap'), false);
    }

    public function test_about_page_renders_correctly(): void
    {
        $this->get('/tentang')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/About')
                ->where('canonicalUrl', route('storefront.about'))
            );
    }

    public function test_contact_page_renders_correctly(): void
    {
        $this->get('/kontak')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Storefront/Contact')
                ->where('canonicalUrl', route('storefront.contact'))
            );
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
                ->where('ogImage', asset('images/og-image.jpg'))
            );
    }

    public function test_category_og_title_and_description_contain_jakarta(): void
    {
        $category = Category::create(['name' => 'Bunga Papan']);

        $this->get("/categories/{$category->slug}")
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->where('ogTitle', 'Jual Bunga Papan Jakarta | Otim Florist')
                ->where('ogDescription', 'Beli Bunga Papan Jakarta murah berkualitas dari Otim Florist. Gratis ongkir Jakbar & Jakpus. Pesan sekarang via WhatsApp.')
            );
    }

    public function test_product_og_title_contains_jakarta(): void
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
                ->where('ogTitle', 'Rose Bouquet M | Otim Florist Jakarta')
            );
    }

    public function test_public_storefront_renders_favicon_links(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('rel="icon" type="image/x-icon" href="'.asset('favicon.ico').'"', false)
            ->assertSee('rel="icon" type="image/svg+xml" href="'.asset('favicon.svg').'"', false)
            ->assertSee('rel="apple-touch-icon" sizes="180x180" href="'.asset('apple-touch-icon.png').'"', false);
    }

    public function test_public_storefront_uses_latest_product_image_for_open_graph(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Bouquet']);
        Product::create([
            'name' => 'First Product',
            'category_id' => $category->id,
            'price' => 100000,
            'image_path' => 'products/first.jpg',
        ]);
        Product::create([
            'name' => 'Latest Product',
            'category_id' => $category->id,
            'price' => 200000,
            'image_path' => 'products/latest.jpg',
        ]);

        $latestUrl = url(Storage::disk('public')->url('products/latest.jpg'));

        $this->get('/')
            ->assertOk()
            ->assertSee('property="og:image" content="'.$latestUrl.'"', false)
            ->assertSee('name="twitter:image" content="'.$latestUrl.'"', false)
            ->assertInertia(fn ($page) => $page
                ->where('ogImage', $latestUrl)
            );
    }

    public function test_not_found_public_pages_render_not_found_page(): void
    {
        $this->get('/halaman-yang-tidak-ada')
            ->assertNotFound()
            ->assertSee('404', false)
            ->assertSee('Halaman Tidak Ditemukan', false);

        $this->get('/products/produk-tidak-ditemukan')
            ->assertNotFound()
            ->assertSee('404', false)
            ->assertSee('Halaman Tidak Ditemukan', false);

        $this->get('/categories/kategori-tidak-ditemukan')
            ->assertNotFound()
            ->assertSee('404', false)
            ->assertSee('Halaman Tidak Ditemukan', false);
    }

    public function test_not_found_json_requests_remain_not_found(): void
    {
        $this->getJson('/api/non-existent-endpoint')
            ->assertNotFound();
    }
}
