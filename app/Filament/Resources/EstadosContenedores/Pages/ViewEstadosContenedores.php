<?php

namespace App\Filament\Resources\EstadosContenedores\Pages;

use App\Filament\Resources\EstadosContenedores\EstadosContenedoresResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEstadosContenedores extends ViewRecord
{
    protected static string $resource = EstadosContenedoresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
