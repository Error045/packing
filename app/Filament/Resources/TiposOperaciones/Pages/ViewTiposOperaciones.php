<?php

namespace App\Filament\Resources\TiposOperaciones\Pages;

use App\Filament\Resources\TiposOperaciones\TiposOperacionesResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTiposOperaciones extends ViewRecord
{
    protected static string $resource = TiposOperacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
