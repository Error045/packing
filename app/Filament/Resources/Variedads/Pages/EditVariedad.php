<?php

namespace App\Filament\Resources\Variedads\Pages;

use App\Filament\Resources\Variedads\VariedadResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVariedad extends EditRecord
{
    protected static string $resource = VariedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
