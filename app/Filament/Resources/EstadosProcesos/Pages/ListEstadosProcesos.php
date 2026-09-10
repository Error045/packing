<?php

namespace App\Filament\Resources\EstadosProcesos\Pages;

use App\Filament\Resources\EstadosProcesos\EstadosProcesosResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEstadosProcesos extends ListRecords
{
    protected static string $resource = EstadosProcesosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
