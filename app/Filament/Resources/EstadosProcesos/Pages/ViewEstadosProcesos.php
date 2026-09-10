<?php

namespace App\Filament\Resources\EstadosProcesos\Pages;

use App\Filament\Resources\EstadosProcesos\EstadosProcesosResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEstadosProcesos extends ViewRecord
{
    protected static string $resource = EstadosProcesosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
