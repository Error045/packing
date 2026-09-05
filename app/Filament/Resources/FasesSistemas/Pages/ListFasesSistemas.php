<?php

namespace App\Filament\Resources\FasesSistemas\Pages;

use App\Filament\Resources\FasesSistemas\FasesSistemaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFasesSistemas extends ListRecords
{
    protected static string $resource = FasesSistemaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
