<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('supplier.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order_date')
                    ->date()
                    ->sortable(),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'Received',
                        'primary' => 'Ordered',
                        'warning' => 'Draft',
                        'danger' => 'Cancelled',
                    ])
                    ->sortable(),
                TextColumn::make('items_count')->counts('items')->label('Jml Item'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Ordered' => 'Sudah Dipesan',
                        'Received' => 'Diterima Penuh',
                        'Cancelled' => 'Dibatalkan',
                    ])
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
