<?php

namespace App\Filament\Resources\Warehouses\Tables;

use App\Models\Warehouse;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;

class WarehousesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->searchable()
                ->sortable(),
                
            TextColumn::make('total_products_count')
                ->label('Jumlah Varian Produk')
                ->counts('stocks') // Menggunakan count dari relasi 'stocks'
                ->sortable(),
                
            // Custom Column untuk menampilkan total Kuantitas (nilai agregat)
            TextColumn::make('total_quantity')
                ->label('Total Kuantitas Stok (Unit)')
                ->getStateUsing(fn (Warehouse $record) => $record->stocks()->sum('quantity'))
                ->numeric(
                    decimalPlaces: 0,
                    thousandsSeparator: ',',
                )
                ->sortable(),
                
            TextColumn::make('address')
                ->limit(50),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
