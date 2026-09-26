<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_products_page_renders_persisted_products_and_categories(): void
    {
        $category = Category::create(['name' => 'Bouquet']);
        Product::create([
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
            'sale_price' => 300000,
        ]);

        $this->get('/products')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Products')
                ->has('categories', 1)
                ->where('categories.0.name', 'Bouquet')
                ->has('products.data', 1)
                ->where('products.data.0.name', 'Rose Bouquet M')
                ->where('products.data.0.category.name', 'Bouquet')
                ->where('products.data.0.price', 350000)
            );
    }

    public function test_products_page_paginates_with_selected_page_size(): void
    {
        $category = Category::create(['name' => 'Bouquet']);

        foreach (range(1, 11) as $number) {
            Product::create([
                'name' => "Product {$number}",
                'category_id' => $category->id,
                'price' => $number * 10000,
            ]);
        }

        $this->get('/products?per_page=10')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('products.data', 10)
                ->where('products.pagination.currentPage', 1)
                ->where('products.pagination.lastPage', 2)
                ->where('products.pagination.perPage', 10)
                ->where('products.pagination.total', 11)
            );

        $this->get('/products?per_page=10&page=2')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('products.data', 1)
                ->where('products.pagination.currentPage', 2)
            );
    }

    public function test_product_can_be_created(): void
    {
        $category = Category::create(['name' => 'Bouquet']);

        $this->post('/products', [
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
            'sale_price' => 300000,
        ])
            ->assertRedirect('/products')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Rose Bouquet M B-01',
            'category_id' => $category->id,
            'price' => 350000,
            'sale_price' => 300000,
            'slug' => 'rose-bouquet-m-b-01',
        ]);
    }

    public function test_created_product_name_gets_incrementing_category_code(): void
    {
        $board = Category::create(['name' => 'Bunga Papan']);
        $bouquet = Category::create(['name' => 'Bouquet']);

        $this->post('/products', [
            'name' => 'Bunga Papan Selamat Ulang Tahun',
            'category_id' => $board->id,
            'price' => 500000,
        ])->assertRedirect('/products');

        $this->post('/products', [
            'name' => 'Bunga Papan Selamat Ulang Tahun',
            'category_id' => $board->id,
            'price' => 550000,
        ])->assertRedirect('/products');

        $this->post('/products', [
            'name' => 'Bunga Papan Selamat Ulang Tahun',
            'category_id' => $bouquet->id,
            'price' => 600000,
        ])->assertRedirect('/products');

        $this->post('/products', [
            'name' => 'Bunga Papan Selamat Ulang Tahun BP-02',
            'category_id' => $board->id,
            'price' => 580000,
        ])->assertRedirect('/products');

        $this->assertEqualsCanonicalizing([
            'Bunga Papan Selamat Ulang Tahun BP-01',
            'Bunga Papan Selamat Ulang Tahun BP-02',
            'Bunga Papan Selamat Ulang Tahun BP-03',
            'Bunga Papan Selamat Ulang Tahun B-01',
        ], Product::query()->pluck('name')->all());

        $this->assertDatabaseHas('products', [
            'name' => 'Bunga Papan Selamat Ulang Tahun BP-01',
            'slug' => 'bunga-papan-selamat-ulang-tahun-bp-01',
        ]);
    }

    public function test_product_image_and_description_can_be_created_updated_and_deleted(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Bouquet']);

        $this->post('/products', [
            'name' => 'Rose Bouquet M',
            'description' => 'A soft pink rose arrangement.',
            'category_id' => $category->id,
            'price' => 350000,
            'image' => UploadedFile::fake()->image('rose.jpg'),
        ])->assertRedirect('/products');

        $product = Product::firstOrFail();
        $firstImagePath = $product->image_path;

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'description' => 'A soft pink rose arrangement.',
        ]);
        Storage::disk('public')->assertExists($firstImagePath);

        $this->put("/products/{$product->id}", [
            'name' => 'Rose Bouquet L',
            'description' => 'A larger pink rose arrangement.',
            'category_id' => $category->id,
            'price' => 450000,
            'image' => UploadedFile::fake()->image('rose-large.jpg'),
        ])->assertRedirect('/products');

        $product->refresh();

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'description' => 'A larger pink rose arrangement.',
        ]);
        Storage::disk('public')->assertMissing($firstImagePath);
        Storage::disk('public')->assertExists($product->image_path);

        $imagePath = $product->image_path;
        $this->delete("/products/{$product->id}")->assertRedirect('/products');

        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_product_fields_must_be_valid(): void
    {
        $this->post('/products', [
            'name' => '',
            'category_id' => 999,
            'price' => -1,
        ])->assertSessionHasErrors(['name', 'category_id', 'price']);
    }

    public function test_product_image_must_not_exceed_three_megabytes(): void
    {
        Storage::fake('public');
        $category = Category::create(['name' => 'Bouquet']);

        $this->post('/products', [
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
            'image' => UploadedFile::fake()->create('huge-rose.jpg', 3073, 'image/jpeg'),
        ])->assertSessionHasErrors('image');

        $this->post('/products', [
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
            'image' => UploadedFile::fake()->create('valid-rose.jpg', 3072, 'image/jpeg'),
        ])->assertSessionDoesntHaveErrors('image');
    }

    public function test_sale_price_must_be_lower_than_regular_price(): void
    {
        $category = Category::create(['name' => 'Bouquet']);

        $this->post('/products', [
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
            'sale_price' => 350000,
        ])->assertSessionHasErrors('sale_price');
    }

    public function test_product_can_be_updated_and_deleted(): void
    {
        $category = Category::create(['name' => 'Bouquet']);
        $product = Product::create([
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
        ]);

        $this->put("/products/{$product->id}", [
            'name' => 'Rose Bouquet L',
            'category_id' => $category->id,
            'price' => 450000,
        ])->assertRedirect('/products');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Rose Bouquet L',
            'price' => 450000,
            'slug' => 'rose-bouquet-l',
        ]);

        $this->delete("/products/{$product->id}")->assertRedirect('/products');

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $category = Category::create(['name' => 'Bouquet']);
        Product::create([
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
        ]);

        $this->delete("/categories/{$category->id}")
            ->assertRedirect('/categories')
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
