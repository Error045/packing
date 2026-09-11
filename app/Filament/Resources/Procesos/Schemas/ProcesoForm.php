<?php

namespace App\Filament\Resources\Procesos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProcesoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('recepciones_id')
                    ->relationship('recepcion', 'id')
                    ->required(),
                DatePicker::make('fecha'),
                TimePicker::make('hora'),
                TextInput::make('descripcion')
                    ->default(null),
                Select::make('estados_procesos_id')
                    ->relationship('estadosProcesos', 'nombre')
                    ->required(),
                Select::make('users_id')
                    ->relationship('user', 'name')
                    ->required(),
                Toggle::make('estado'),
            ]);
    }
}
