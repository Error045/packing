<?php

namespace App\Filament\Resources\TiposUbicaciones\Pages;

use App\Filament\Resources\TiposUbicaciones\TiposUbicacionesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTiposUbicaciones extends ViewRecord
{
    protected static string $resource = TiposUbicacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
