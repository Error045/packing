<?php

namespace App\Filament\Resources\EstadosContenedores\Pages;

use App\Filament\Resources\EstadosContenedores\EstadosContenedoresResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEstadosContenedores extends ListRecords
{
    protected static string $resource = EstadosContenedoresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
