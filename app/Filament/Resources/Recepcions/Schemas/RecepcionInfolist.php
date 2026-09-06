<?php

namespace App\Filament\Resources\Recepcions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RecepcionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tipos_recepciones_id')
                    ->numeric(),
                TextEntry::make('personas_id')
                    ->numeric(),
                TextEntry::make('fecha')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('hora')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('estados_recepciones_id')
                    ->numeric(),
                TextEntry::make('users_id')
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
