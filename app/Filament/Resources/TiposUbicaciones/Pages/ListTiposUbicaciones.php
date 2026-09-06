<?php

namespace App\Filament\Resources\TiposUbicaciones\Pages;

use App\Filament\Resources\TiposUbicaciones\TiposUbicacionesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTiposUbicaciones extends ListRecords
{
    protected static string $resource = TiposUbicacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
