<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_root_redirects_to_dashboard(): void
    {
        $this->get('/')->assertRedirect('/dashboard');
    }

    public function test_overview_renders_with_dummy_props(): void
    {
        $this->get('/dashboard')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Dashboard/Overview')
                ->has('stats', 4)
                ->has('sales', 7)
                ->has('orders', 5)
                ->has('lowStock', 3)
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

        $this->from('/settings')->put('/settings', [
            'name' => '',
            'phone' => '',
            'address' => '',
            'hours' => '',
        ])->assertSessionHasErrors(['name', 'phone', 'address', 'hours']);
    }
}
