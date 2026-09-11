<?php

namespace App\Filament\Resources\Procesos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProcesoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('recepciones_id')
                    ->numeric(),
                TextEntry::make('fecha')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('hora')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('descripcion')
                    ->placeholder('-'),
                TextEntry::make('estadosProcesos.nombre')
                    ->label('Estados procesos'),
                TextEntry::make('user.name')
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
