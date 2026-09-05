<?php

namespace App\Filament\Resources\TiposRecepciones\Pages;

use App\Filament\Resources\TiposRecepciones\TiposRecepcionesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTiposRecepciones extends ListRecords
{
    protected static string $resource = TiposRecepcionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
