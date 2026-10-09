<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
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

        $this->get('/admin/categories')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Categories')
                ->has('categories.data', 1)
                ->where('categories.data.0.name', 'Bouquet')
            );
    }

    public function test_categories_page_paginates_with_selected_page_size(): void
    {
        foreach (range(1, 11) as $number) {
            Category::create(['name' => "Category {$number}"]);
        }

        $this->get('/admin/categories?per_page=10')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('categories.data', 10)
                ->where('categories.pagination.currentPage', 1)
                ->where('categories.pagination.lastPage', 2)
                ->where('categories.pagination.perPage', 10)
                ->where('categories.pagination.total', 11)
            );

        $this->get('/admin/categories?per_page=10&page=2')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->has('categories.data', 1)
                ->where('categories.pagination.currentPage', 2)
            );
    }

    public function test_category_can_be_created(): void
    {
        $this->post('/admin/categories', ['name' => 'Basket'])
            ->assertRedirect('/admin/categories')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['name' => 'Basket', 'slug' => 'basket']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::create(['name' => 'Bouquet']);

        $this->post('/admin/categories', ['name' => 'Bouquet'])
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

        $this->put("/admin/categories/{$category->id}", ['name' => 'Premium Bouquet'])
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseHas('categories', ['name' => 'Premium Bouquet', 'slug' => 'premium-bouquet']);

        $this->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_navigation_categories_are_cached_and_invalidated_on_mutations(): void
    {
        $this->assertFalse(Cache::has(Category::CACHE_KEY_NAVIGATION));

        Category::create(['name' => 'Standing Flower']);

        $navigation = Category::navigation();
        $this->assertCount(1, $navigation);
        $this->assertSame('Standing Flower', $navigation[0]['name']);
        $this->assertTrue(Cache::has(Category::CACHE_KEY_NAVIGATION));

        // Create another category -> cache should be invalidated
        $this->post('/admin/categories', ['name' => 'Buket Mawar'])
            ->assertRedirect('/admin/categories');

        $this->assertFalse(Cache::has(Category::CACHE_KEY_NAVIGATION));

        $updatedNavigation = Category::navigation();
        $this->assertCount(2, $updatedNavigation);
        $this->assertSame('Buket Mawar', $updatedNavigation[0]['name']);
        $this->assertSame('Standing Flower', $updatedNavigation[1]['name']);

        // Update category -> cache should be invalidated
        $category = Category::where('name', 'Buket Mawar')->firstOrFail();
        $this->put("/admin/categories/{$category->id}", ['name' => 'Buket Lily'])
            ->assertRedirect('/admin/categories');

        $this->assertFalse(Cache::has(Category::CACHE_KEY_NAVIGATION));

        $afterUpdate = Category::navigation();
        $this->assertSame('Buket Lily', $afterUpdate[0]['name']);

        // Delete category -> cache should be invalidated
        $this->delete("/admin/categories/{$category->id}")
            ->assertRedirect('/admin/categories');

        $this->assertFalse(Cache::has(Category::CACHE_KEY_NAVIGATION));

        $afterDelete = Category::navigation();
        $this->assertCount(1, $afterDelete);
        $this->assertSame('Standing Flower', $afterDelete[0]['name']);
    }
}
