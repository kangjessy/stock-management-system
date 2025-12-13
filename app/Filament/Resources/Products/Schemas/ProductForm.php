<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use App\Models\Product;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Select::make('warehouse_id')
                //             ->relationship('warehouse', 'name')
                //             ->label('Gudang')
                //             ->required(),

                Section::make('Informasi Dasar Produk')
                    ->columns(3)
                    ->schema([
                        TextInput::make('sku')
                            ->label('SKU (Stock Keeping Unit)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('name')
                            ->label('Nama Produk')
                            ->required()
                            ->columnSpan(2)
                            ->maxLength(255),

                        // Relasi menggunakan Select
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->label('Kategori')
                            ->searchable()
                            ->preload(),
                        Select::make('unit_id')
                            ->relationship('unit', 'name')
                            ->label('Satuan Unit (UoM)')
                            ->searchable()
                            ->preload(),

                        FileUpload::make('image')
                            ->label('Gambar Produk')
                            ->image()
                            ->directory('product-images')
                            ->columnSpan(3),

                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpan(3),

                        

                        // TextInput::make('quantity')
                        //     ->label('Kuantitas Saat Ini')
                        //     ->numeric()
                        //     ->disabled(),
                    ]),

                Section::make('Informasi Harga dan Batas Stok')
                    ->columns(3)
                    ->schema([
                        TextInput::make('price_in')
                            ->label('Harga Beli Rata-Rata')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                            
                        TextInput::make('price_out')
                            ->label('Harga Jual')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                        TextInput::make('reorder_point')
                            ->label('Stok Minimum (Reorder Point)')
                            ->numeric()
                            ->default(0),
                    ]),
            ]);
    }
}
