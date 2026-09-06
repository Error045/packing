<?php

namespace App\Filament\Resources\TiposUbicaciones\Pages;

use App\Filament\Resources\TiposUbicaciones\TiposUbicacionesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTiposUbicaciones extends EditRecord
{
    protected static string $resource = TiposUbicacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
