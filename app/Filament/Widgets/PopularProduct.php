<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PopularProduct extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Top 5 most visited product';
    public function table(Table $table): Table
    {
        return $table
            ->query(Product::latest()->take(5))
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->square(),
                TextColumn::make('product_name')
                    ->label('Product'),
            ])->paginated(false);
    }
}
