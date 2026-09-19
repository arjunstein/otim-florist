<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_categories_page_renders_categories(): void
    {
        Category::create(['name' => 'Bouquet']);

        $this->get('/categories')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Categories')
                ->has('categories', 1)
                ->where('categories.0.name', 'Bouquet')
            );
    }

    public function test_category_can_be_created(): void
    {
        $this->post('/categories', ['name' => 'Basket'])
            ->assertRedirect('/categories')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['name' => 'Basket', 'slug' => 'basket']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::create(['name' => 'Bouquet']);

        $this->post('/categories', ['name' => 'Bouquet'])
            ->assertSessionHasErrors('name');
    }

    public function test_category_slug_stays_unique_when_names_normalize_the_same(): void
    {
        Category::create(['name' => 'Fresh Flowers']);
        Category::create(['name' => 'Fresh-Flowers']);

        $this->assertDatabaseHas('categories', ['slug' => 'fresh-flowers']);
        $this->assertDatabaseHas('categories', ['slug' => 'fresh-flowers-2']);
    }

    public function test_category_can_be_updated_and_deleted(): void
    {
        $category = Category::create(['name' => 'Bouquet']);

        $this->put("/categories/{$category->id}", ['name' => 'Premium Bouquet'])
            ->assertRedirect('/categories');

        $this->assertDatabaseHas('categories', ['name' => 'Premium Bouquet', 'slug' => 'premium-bouquet']);

        $this->delete("/categories/{$category->id}")
            ->assertRedirect('/categories');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
