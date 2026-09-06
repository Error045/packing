<?php

namespace App\Filament\Resources\Recepcions\Pages;

use App\Filament\Resources\Recepcions\RecepcionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewDetalleRecepcion extends ViewRecord
{
    protected static string $resource = RecepcionResource::class;


    protected function getHeaderActions(): array
{
    return [
        EditAction::make(),

        Action::make('imprimir')
            ->label('Imprimir Recepción')
            ->icon('heroicon-o-printer')
            ->color('info')
            ->url(fn ($record) => route('recepciones.imprimir', $record))
            ->openUrlInNewTab(),
    ];
}


}
