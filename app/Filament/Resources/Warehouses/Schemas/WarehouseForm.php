<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->label('Nama Gudang')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),
            
            Textarea::make('address')
                ->label('Alamat Lengkap')
                ->nullable()
                ->columnSpan('full'),
            ]);
    }
}
