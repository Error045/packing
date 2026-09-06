<?php

namespace App\Filament\Resources\EstadosContenedores\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EstadosContenedoresForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required(),
                Select::make('fases_sistema_id')
                    ->relationship('faseSistema', 'nombre')
                    ->required(),
                TextInput::make('descripcion')
                    ->default(null),
                Toggle::make('estado')
                    ->default(true),
            ]);
    }
}
