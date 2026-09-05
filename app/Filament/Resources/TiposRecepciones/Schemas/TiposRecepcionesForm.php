<?php

namespace App\Filament\Resources\TiposRecepciones\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TiposRecepcionesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tipo')
                    ->required(),
                Textarea::make('descripcion')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('tipos_operaciones_id')
                    ->relationship('tipoOperacion', 'nombre')
                    ->required()
                    ->searchable()
                    ->preload(),
                Toggle::make('estado')
                    ->required(),
            ]);
    }
}
