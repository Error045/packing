<?php

namespace App\Filament\Resources\Calibres\Pages;

use App\Filament\Resources\Calibres\CalibreResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCalibre extends ViewRecord
{
    protected static string $resource = CalibreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
