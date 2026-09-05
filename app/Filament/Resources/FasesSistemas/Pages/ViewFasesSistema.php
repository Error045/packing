<?php

namespace App\Filament\Resources\FasesSistemas\Pages;

use App\Filament\Resources\FasesSistemas\FasesSistemaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFasesSistema extends ViewRecord
{
    protected static string $resource = FasesSistemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
