<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_overview_renders_catalog_summary(): void
    {
        $category = Category::create(['name' => 'Bouquet']);
        $product = Product::create([
            'name' => 'Rose Bouquet M',
            'category_id' => $category->id,
            'image_path' => 'products/rose.jpg',
            'price' => 350000,
            'sale_price' => 300000,
        ]);
        Product::query()->whereKey($product->getKey())->update(['click_count' => 12]);

        $this->get('/dashboard')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Overview')
                ->has('stats', 4)
                ->where('stats.0.value', '1')
                ->where('stats.2.value', '1')
                ->where('recentProducts.0.name', 'Rose Bouquet M')
                ->where('recentProducts.0.salePrice', 300000)
                ->where('categorySummary.0.name', 'Bouquet')
                ->where('mostClickedProducts.0.name', 'Rose Bouquet M')
                ->where('mostClickedProducts.0.imageUrl', Storage::disk('public')->url('products/rose.jpg'))
                ->where('mostClickedProducts.0.clickCount', 12)
            );
    }

    public function test_settings_renders_with_store_prop(): void
    {
        $this->get('/settings')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Settings')
                ->where('store.name', 'Otim Florist')
            );
    }

    public function test_settings_update_validates_and_redirects_with_flash(): void
    {
        $this->from('/settings')->put('/settings', [
            'name' => 'Otim Florist',
            'phone' => '+62 812-0000-0000',
            'address' => 'Jl. Mawar No. 12, Jakarta',
            'hours' => '08:00–20:00 daily',
        ])
            ->assertRedirect('/settings')
            ->assertSessionHas('success');

        $this->assertDatabaseHas('store_settings', [
            'name' => 'Otim Florist',
            'phone' => '6281200000000',
        ]);

        $this->from('/settings')->put('/settings', [
            'name' => '',
            'phone' => '',
            'address' => '',
            'hours' => '',
        ])->assertSessionHasErrors(['name', 'phone', 'address', 'hours']);
    }
}
