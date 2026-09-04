<?php

namespace App\Filament\Resources\Personas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PersonaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('rut')
                    ->required(),
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('empresa')
                    ->default(null),
                TextInput::make('telefono')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Correo electrónico')
                    ->email()
                    ->required(),
                Toggle::make('estado'),
            ]);
    }
}
