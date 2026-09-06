<?php

namespace App\Filament\Resources\Variedads\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class VariedadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                Textarea::make('descripcion')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('producto_id')
                    ->relationship('producto', 'nombre')
                    ->required(),
                Toggle::make('estado')
                    ->default(true)
                    ->required(),
            ]);
    }
}
