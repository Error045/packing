<?php

namespace App\Filament\Resources\TiposContenedores\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TiposContenedoresInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre'),
                TextEntry::make('tara')
                    ->numeric(),
                TextEntry::make('capacidad')
                    ->numeric(),
                TextEntry::make('material')
                    ->placeholder('-'),
                TextEntry::make('dimensiones')
                    ->placeholder('-'),
                TextEntry::make('descripcion')
                    ->placeholder('-'),
                TextEntry::make('tipos_clases')
                    ->numeric(),
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
