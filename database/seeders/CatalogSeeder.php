<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect(['Bouquets', 'Roses', 'Gift Sets', 'Seasonal'])
            ->mapWithKeys(fn (string $name) => [$name => Category::query()->updateOrCreate(['name' => $name])]);

        foreach ([
            ['name' => 'Classic Garden Bouquet', 'category' => 'Bouquets', 'price' => 275000],
            ['name' => 'Rose Romance', 'category' => 'Roses', 'price' => 350000],
            ['name' => 'Sunflower Cheer', 'category' => 'Bouquets', 'price' => 220000],
            ['name' => 'Tulip Delight', 'category' => 'Seasonal', 'price' => 300000],
            ['name' => 'Blush Rose Box', 'category' => 'Gift Sets', 'price' => 425000],
            ['name' => 'White Lily Basket', 'category' => 'Gift Sets', 'price' => 325000],
            ['name' => 'Peach Rose Posy', 'category' => 'Roses', 'price' => 185000],
            ['name' => 'Wildflower Morning', 'category' => 'Seasonal', 'price' => 245000],
        ] as $product) {
            Product::query()->updateOrCreate(
                ['name' => $product['name']],
                [
                    'category_id' => $categories[$product['category']]->id,
                    'price' => $product['price'],
                ],
            );
        }
    }
}
