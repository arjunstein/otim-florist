<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestVisitor extends BaseWidget
{
    protected static ?int $sort = 3;
    protected static ?string $heading = 'Latest 5 visitors';

    public function table(Table $table): Table
    {
        return $table
            ->query(Visitor::latest()->take(5))
            ->columns([
                TextColumn::make('ip')
                    ->label('IP'),
                TextColumn::make('os')
                    ->label('OS name'),
                TextColumn::make('created_at')
                    ->label('Last visited'),
            ])->paginated(false);
    }
}
