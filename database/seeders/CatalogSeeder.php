<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Bunga Papan',
            'Bunga Standing',
            'Bunga Meja',
            'Bunga Salib',
            'Hand Bouquet',
        ] as $name) {
            Category::query()->updateOrCreate(['name' => $name]);
        }
    }
}
