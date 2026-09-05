<?php

namespace App\Filament\Resources\TiposOperaciones\Pages;

use App\Filament\Resources\TiposOperaciones\TiposOperacionesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTiposOperaciones extends ListRecords
{
    protected static string $resource = TiposOperacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
