<?php

namespace App\Filament\Resources\TiposOperaciones\Pages;

use App\Filament\Resources\TiposOperaciones\TiposOperacionesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTiposOperaciones extends EditRecord
{
    protected static string $resource = TiposOperacionesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
