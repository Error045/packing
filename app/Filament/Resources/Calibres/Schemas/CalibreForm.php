<?php

namespace App\Filament\Resources\Calibres\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CalibreForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('numero')
                    ->numeric()
                    ->default(null),
                Textarea::make('descripcion')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('variedades_id')
                    ->relationship('variedades', 'nombre')
                    ->required(),
                TextInput::make('estados_calibres_id')
                    ->required()
                    ->numeric(),
                Toggle::make('estado')
                    ->default(true)
                    ->required(),
            ]);
    }
}
