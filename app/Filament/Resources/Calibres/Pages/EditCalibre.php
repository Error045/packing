<?php

namespace App\Filament\Resources\Calibres\Pages;

use App\Filament\Resources\Calibres\CalibreResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCalibre extends EditRecord
{
    protected static string $resource = CalibreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
