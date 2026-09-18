<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

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

        $this->assertDatabaseHas('categories', ['name' => 'Basket']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::create(['name' => 'Bouquet']);

        $this->post('/categories', ['name' => 'Bouquet'])
            ->assertSessionHasErrors('name');
    }

    public function test_category_can_be_updated_and_deleted(): void
    {
        $category = Category::create(['name' => 'Bouquet']);

        $this->put("/categories/{$category->id}", ['name' => 'Premium Bouquet'])
            ->assertRedirect('/categories');

        $this->assertDatabaseHas('categories', ['name' => 'Premium Bouquet']);

        $this->delete("/categories/{$category->id}")
            ->assertRedirect('/categories');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
