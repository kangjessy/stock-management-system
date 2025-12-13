<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->recordTitleAttribute('warehouse_id')
            ->columns([
                ImageColumn::make('image')
                ->square(),
            TextColumn::make('sku')
                ->searchable()
                ->sortable(),
            TextColumn::make('name')
                ->searchable()
                ->sortable(),
            TextColumn::make('category.name'),
            TextColumn::make('unit.name'),

            // Custom Column untuk Menghitung Total Stok dari Semua Gudang
            TextColumn::make('total_stock')
                ->label('Total Stok QOH')
                ->getStateUsing(fn (Product $record) => $record->stocks()->sum('quantity'))
                // ->color()
                ->sortable(query: fn (Builder $query, string $direction) => 
                    // Memungkinkan sorting berdasarkan total stok
                    $query->withSum('stocks', 'quantity')->orderBy('stocks_sum_quantity', $direction)
                )
                ->numeric(
                    decimalPlaces: 0,
                    thousandsSeparator: ',',
                ),
                
            TextColumn::make('reorder_point')
                ->label('Batas Min')
                ->numeric()
                ->sortable(),
            
            TextColumn::make('price_out')
                ->money('IDR') // Sesuaikan dengan mata uang
                ->label('Harga Jual'),

            //     TextColumn::make('warehouse.name'),
            // TextColumn::make('quantity')
            //     ->numeric()
            //     ->label('Stok Saat Ini'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                ->relationship('category', 'name')
                ->label('Filter Kategori'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
