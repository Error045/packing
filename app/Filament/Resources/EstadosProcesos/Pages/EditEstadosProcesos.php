<?php

namespace App\Filament\Resources\EstadosProcesos\Pages;

use App\Filament\Resources\EstadosProcesos\EstadosProcesosResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEstadosProcesos extends EditRecord
{
    protected static string $resource = EstadosProcesosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
