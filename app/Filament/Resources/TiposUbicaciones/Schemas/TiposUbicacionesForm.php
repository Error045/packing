<?php

namespace App\Filament\Resources\TiposUbicaciones\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TiposUbicacionesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('funciones_id')
                    ->required()
                    ->numeric(),
                TextInput::make('capacidad')
                    ->required()
                    ->numeric(),
                TextInput::make('descripcion')
                    ->default(null),
                Toggle::make('estado')
                    ->default(true),
            ]);
    }
}
