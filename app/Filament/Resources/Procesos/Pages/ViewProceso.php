<?php

namespace App\Filament\Resources\Procesos\Pages;

use App\Filament\Resources\Procesos\ProcesoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProceso extends ViewRecord
{
    protected static string $resource = ProcesoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
