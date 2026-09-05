<?php

namespace App\Filament\Resources\TiposRecepciones\Pages;

use App\Filament\Resources\TiposRecepciones\TiposRecepcionesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTiposRecepciones extends EditRecord
{
    protected static string $resource = TiposRecepcionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
