<?php

namespace App\Filament\Resources\TiposContenedores\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TiposContenedoresForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('tara')
                    ->required()
                    ->numeric(),
                TextInput::make('capacidad')
                    ->required()
                    ->numeric(),
                TextInput::make('material')
                    ->default(null),
                TextInput::make('dimensiones')
                    ->default(null),
                TextInput::make('descripcion')
                    ->default(null),
                TextInput::make('tipos_clases')
                    ->required()
                    ->numeric(),
                Toggle::make('estado')
                    ->default(true),
            ]);
    }
}
