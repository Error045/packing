<?php

namespace App\Filament\Resources\TiposOperaciones\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TiposOperacionesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('nombre'),
                IconEntry::make('estado')
                    ->boolean()
                    ->placeholder('-'),
            ]);
    }
}
