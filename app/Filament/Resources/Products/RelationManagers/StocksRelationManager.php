<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Tables\Table;
use Filament\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\RelationManagers\RelationManager;
use App\Models\Stock;

class StocksRelationManager extends RelationManager
{
    protected static string $relationship = 'stocks';

    protected static ?string $relatedResource = ProductResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('warehouse.name') // Menampilkan nama gudang
                    ->label('Gudang')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Kuantitas Stok')
                    ->numeric()
                    ->sortable(),
                    
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Jika Anda ingin mengizinkan pembuatan stok manual (tidak disarankan)
                // Tables\Actions\CreateAction::make(), 
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
    }
}
