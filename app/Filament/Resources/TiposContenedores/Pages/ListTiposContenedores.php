<?php

namespace App\Filament\Resources\TiposContenedores\Pages;

use App\Filament\Resources\TiposContenedores\TiposContenedoresResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTiposContenedores extends ListRecords
{
    protected static string $resource = TiposContenedoresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
