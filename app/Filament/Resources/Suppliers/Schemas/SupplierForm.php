<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Pemasok/Perusahaan')
                    ->required(),
                TextInput::make('contact_person')
                    ->label('Nama Kontak Person')
                    ->nullable(),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->tel()
                    ->nullable(),
                Textarea::make('address')
                    ->label('Alamat')
                    ->columnSpan('full')
                    ->nullable(),
            ]);
    }
}
