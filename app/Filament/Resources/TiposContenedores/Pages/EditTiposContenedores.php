<?php

namespace App\Filament\Resources\TiposContenedores\Pages;

use App\Filament\Resources\TiposContenedores\TiposContenedoresResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTiposContenedores extends EditRecord
{
    protected static string $resource = TiposContenedoresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
