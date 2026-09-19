<?php

namespace Tests\Feature;

use Database\Seeders\CatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_seeder_creates_idempotent_categories_without_products(): void
    {
        $this->seed(CatalogSeeder::class);
        $this->seed(CatalogSeeder::class);

        $this->assertDatabaseCount('categories', 5);
        $this->assertDatabaseCount('products', 0);
        $this->assertDatabaseHas('categories', ['name' => 'Bunga Papan', 'slug' => 'bunga-papan']);
        $this->assertDatabaseHas('categories', ['name' => 'Hand Bouquet', 'slug' => 'hand-bouquet']);
    }
}
