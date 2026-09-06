<?php

namespace App\Filament\Resources\EstadosContenedores\Pages;

use App\Filament\Resources\EstadosContenedores\EstadosContenedoresResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEstadosContenedores extends EditRecord
{
    protected static string $resource = EstadosContenedoresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
