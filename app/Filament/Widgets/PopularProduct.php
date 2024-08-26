<?php

namespace App\Filament\Widgets;

use App\Models\Popular;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularProduct extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Top 10 most visited product';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Popular::selectRaw('MAX(id) as id,product_id, count(*) as visit, MAX(created_at) as last_visited')
                    ->with('product')
                    ->groupBy('product_id')
                    ->orderByDesc('visit')
                    ->take(10)
            )
            ->columns([
                ImageColumn::make('product.image')
                    ->label('Gambar')
                    ->square(),
                TextColumn::make('product.product_name')
                    ->label('Product'),
                TextColumn::make('product.price')
                    ->label('Price')
                    ->money('Rp.'),
                TextColumn::make('product.sale_price')
                    ->label('Promo price')
                    ->money('Rp.'),
                TextColumn::make('visit')
                    ->label('Total visited'),
                TextColumn::make('last_visited')
                    ->label('Last visited')
            ])->paginated(false);
    }
}
