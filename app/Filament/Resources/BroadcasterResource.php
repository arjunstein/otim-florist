<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BroadcasterResource\Pages;
use App\Filament\Resources\BroadcasterResource\RelationManagers;
use App\Models\Broadcaster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BroadcasterResource extends Resource
{
    protected static ?string $model = Broadcaster::class;

    protected static ?string $navigationIcon = 'heroicon-c-speaker-wave';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subscriber_id')
                    ->label('Receiver')
                    ->relationship('subscribers', 'email')
                    ->required(),
                Forms\Components\TextInput::make('subject')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('messages')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subscriber_id')
                    ->label('Receiver')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBroadcasters::route('/'),
            'create' => Pages\CreateBroadcaster::route('/create'),
            'edit' => Pages\EditBroadcaster::route('/{record}/edit'),
        ];
    }
}
