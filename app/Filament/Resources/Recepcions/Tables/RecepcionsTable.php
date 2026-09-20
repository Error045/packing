<?php

namespace App\Filament\Resources\Recepcions\Tables;

use App\Models\Recepcion;
use App\Filament\Resources\Recepcions\RecepcionResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;


// Acciones principales en Filament v5

use Filament\Actions\ActionGroup;

use Filament\Actions\DeleteAction;

// Componentes de maquetación/layout en Filament v5
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;

// Componentes de formulario

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;

class RecepcionsTable
{
    private static function tienePrecios(Recepcion $record): bool
    {
        return DB::table('productos_precios')
            ->where('recepciones_id', $record->id)
            ->exists();
    }

    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('N° Rec')
                    ->searchable()
                    ->sortable(),


                //TextColumn::make('tipos_recepciones_id')
                //    ->numeric()
                //    ->sortable(),
                TextColumn::make('TiposRecepciones.tipo')
                    ->label('Tipo')
                    ->badge(),
                // TextColumn::make('personas_id')
                //      ->numeric()
                //      ->sortable(),

                TextColumn::make('persona.nombre')
                    ->label('Productor/Cliente')
                    ->searchable() // Permite buscar por el nombre del productor
                    ->sortable(),  // Permite ordenar alfabéticamente

                TextColumn::make('persona.empresa')
                    ->label('Empresa')
                    ->searchable() // Permite buscar por el nombre del productor
                    ->sortable(),  // Permite ordenar alfabéticamente

                TextColumn::make('fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('hora')
                    ->time()
                    ->sortable(),
                //   TextColumn::make('estados_recepciones_id')
                //       ->numeric()
                //       ->sortable(),


                TextColumn::make('estadoRecepcion.nombre')
                    ->label('Estado')
                    ->sortable(),


                TextColumn::make('users_id')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('estado')
                    ->boolean(),

                TextColumn::make('valorizacion')
                    ->label('$')
                    ->state(function ($record) {
                        $tienePrecios = DB::table('productos_precios')
                            ->where('recepciones_id', $record->id)
                            ->exists();

                        return $tienePrecios ? 'Valorizado' : 'Pendiente';
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Valorizado' => 'success',
                        'Pendiente' => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]) // // fin ->columns

            ->defaultSort('id', 'desc')
            ->actions([
                ActionGroup::make([

                    // 1. Ir a la página de Detalle
                    Action::make('detalle')
                        ->label('Ver Detalles')
                        ->icon('heroicon-o-eye')
                        ->color('info')
                        ->url(fn(Recepcion $record): string => RecepcionResource::getUrl('detalle', ['record' => $record])),

                    // 2. Ir a la página de Calibrado
                    Action::make('calibrado')
                        ->label('Calibrado')
                        ->icon('heroicon-o-scale')
                        ->color('warning')
                        ->url(fn(Recepcion $record): string => RecepcionResource::getUrl('calibrado', ['record' => $record])),

                    Action::make('verValorizacion')
                        ->label('Ver Valorización')
                        ->icon('heroicon-o-banknotes')
                        ->color('success')
                        ->url(fn(Recepcion $record): string => RecepcionResource::getUrl('valorizacion', ['record' => $record])),

                    // 3. Asignar Precios. Solo visible si aun no hay precios
                    Action::make('precios')
                        ->label('Asignar Precios')
                        ->icon('heroicon-o-currency-dollar')
                        ->color('success')
                        ->visible(fn(Recepcion $record): bool => ! self::tienePrecios($record))
                        ->url(fn(Recepcion $record): string => RecepcionResource::getUrl('precios', ['record' => $record])),

                    // 4. Editar Valorización. Solo visible si hay precios asignados
                    Action::make('editarValorizacion')
                        ->label('Editar Precios')
                        ->icon('heroicon-o-pencil-square')
                        ->color('info')
                        ->visible(fn(Recepcion $record): bool => self::tienePrecios($record))
                        ->url(fn(Recepcion $record): string => RecepcionResource::getUrl('editar-valorizacion', ['record' => $record])),

                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ]); // // fin ->actions
    } // // fin método configure()
} // // fin clase RecepcionsTable