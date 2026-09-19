<?php

namespace Tests\Feature;

use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_seeder_creates_idempotent_public_catalog_data(): void
    {
        $this->seed(CatalogSeeder::class);
        $this->seed(CatalogSeeder::class);

        $this->assertDatabaseCount('categories', 4);
        $this->assertDatabaseCount('products', 8);
        $this->assertDatabaseHas('categories', ['name' => 'Bouquets', 'slug' => 'bouquets']);
        $this->assertDatabaseHas('products', ['name' => 'Rose Romance', 'slug' => 'rose-romance', 'price' => 350000]);
    }
}
