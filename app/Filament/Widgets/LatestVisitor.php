<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestVisitor extends BaseWidget
{
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Latest 10 visitors';

    public function table(Table $table): Table
    {
        return $table
            ->query(Visitor::latest()->take(10))
            ->columns([
                TextColumn::make('ip')
                    ->label('IP'),
                TextColumn::make('os')
                    ->label('OS name'),
                TextColumn::make('browser')
                    ->label('Browser'),
                TextColumn::make('device_type')
                    ->label('Device type'),
                TextColumn::make('created_at')
                    ->label('Last visited'),
            ])->paginated(false);
    }
}
