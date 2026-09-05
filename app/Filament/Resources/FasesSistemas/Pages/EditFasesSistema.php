<?php

namespace App\Filament\Resources\FasesSistemas\Pages;

use App\Filament\Resources\FasesSistemas\FasesSistemaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFasesSistema extends EditRecord
{
    protected static string $resource = FasesSistemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
