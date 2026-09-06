<?php

namespace App\Filament\Resources\Variedads\Pages;

use App\Filament\Resources\Variedads\VariedadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVariedads extends ListRecords
{
    protected static string $resource = VariedadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
