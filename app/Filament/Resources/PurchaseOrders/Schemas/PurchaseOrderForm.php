<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\DatePicker;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Informasi Dasar Pesanan')
                            ->columns(2)
                            ->schema([
                                Select::make('supplier_id')
                                    ->relationship('supplier', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                
                                Select::make('warehouse_id')
                                    ->label('Gudang Tujuan Stok')
                                    ->relationship('warehouse', 'name') // Asumsi relasi 'warehouse' ada di Model PO
                                    ->required()
                                    ->searchable()
                                    ->preload(),
                                    
                                DatePicker::make('order_date')
                                    ->default(now())
                                    ->required(),
                                TextInput::make('reference_number')
                                    ->label('Nomor Referensi PO')
                                    ->nullable()
                                    ->columnSpan('full'),
                                Textarea::make('notes')
                                    ->columnSpan('full'),
                            ]),

                        // --- REPEATER UNTUK PURCHASE ITEMS (Detail PO) ---
                        Section::make('Detail Produk yang Dipesan')
                            ->schema([
                                Repeater::make('items')
                                    ->relationship('items') // Relasi hasMany ke PurchaseItem
                                    ->schema([
                                        Select::make('product_id')
                                            ->relationship('product', 'name')
                                            ->required()
                                            ->searchable()
                                            ->preload()
                                            ->columnSpan(4), 
                                        TextInput::make('quantity')
                                            ->numeric()
                                            ->required()
                                            ->columnSpan(4),
                                        TextInput::make('price')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->required()
                                            ->columnSpan(4),
                                    ])
                                    ->columns(12) // Mengatur kolom di dalam repeater
                                    ->defaultItems(1)
                                    ->minItems(1)
                                    ->reorderable(true)
                                    ->addActionLabel('Tambah Produk'),
                            ]),
                    ])->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make('Status Pesanan')
                            ->schema([
                                Select::make('status')
                                    ->options([
                                        'Draft' => 'Draft',
                                        'Ordered' => 'Sudah Dipesan',
                                        'Received' => 'Diterima Penuh (STOCK IN)',
                                        'Cancelled' => 'Dibatalkan',
                                    ])
                                    ->required()
                                    ->default('Draft')
                                    ->native(false)
                                    ->helperText('Mengubah status menjadi "Received" akan menambah stok inventaris secara permanen.'),
                            ]),
                    ])->columnSpan(['lg' => 1]),

            ])->columns(3);
    }
}
