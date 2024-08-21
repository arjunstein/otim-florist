<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $all_products = Cache::remember("all-products", 120, function () {
            return Product::count();
        });

        $all_categories = Cache::remember("all-categories", 120, function () {
            return Category::count();
        });

        $product_promo = Cache::remember("product-promo", 120, function () {
            return Product::where('sale_price', '!=', null)->count();
        });

        return [
            Stat::make('Total Produk', $all_products),
            Stat::make('Total Kategori', $all_categories),
            Stat::make('Produk Promo', $product_promo),
        ];
    }
}
