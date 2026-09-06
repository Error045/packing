<?php

namespace App\Filament\Resources\Calibres\Pages;

use App\Filament\Resources\Calibres\CalibreResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCalibres extends ListRecords
{
    protected static string $resource = CalibreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
