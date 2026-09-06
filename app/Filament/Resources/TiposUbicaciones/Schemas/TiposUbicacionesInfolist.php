<?php

namespace App\Filament\Resources\TiposUbicaciones\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TiposUbicacionesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre'),
                TextEntry::make('funciones_id')
                    ->numeric(),
                TextEntry::make('capacidad')
                    ->numeric(),
                TextEntry::make('descripcion')
                    ->placeholder('-'),
                IconEntry::make('estado')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
