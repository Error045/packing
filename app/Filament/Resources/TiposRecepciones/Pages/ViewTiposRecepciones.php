<?php

namespace App\Filament\Resources\TiposRecepciones\Pages;

use App\Filament\Resources\TiposRecepciones\TiposRecepcionesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTiposRecepciones extends ViewRecord
{
    protected static string $resource = TiposRecepcionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
