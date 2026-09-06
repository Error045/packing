<?php

namespace App\Filament\Resources\Variedads\Pages;

use App\Filament\Resources\Variedads\VariedadResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVariedad extends ViewRecord
{
    protected static string $resource = VariedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
