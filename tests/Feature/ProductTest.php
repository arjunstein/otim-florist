<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
        ])
            ->assertRedirect('/products')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'price' => 350000,
            'slug' => 'rose-bouquet-m',
        ]);
    }

    public function test_product_fields_must_be_valid(): void
    {
        $this->post('/products', [
            'name' => '',
            'category_id' => 999,
            'price' => -1,
        ])->assertSessionHasErrors(['name', 'category_id', 'price']);
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
