<?php

namespace App\Filament\Resources\TiposContenedores\Pages;

use App\Filament\Resources\TiposContenedores\TiposContenedoresResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTiposContenedores extends ViewRecord
{
    protected static string $resource = TiposContenedoresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
