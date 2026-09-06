<?php

namespace App\Filament\Resources\Recepcions\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\DissociateBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Database\Eloquent\Builder;

class ContenedoresRelationManager extends RelationManager
{
    protected static string $relationship = 'contenedores';

    protected static ?string $title = 'Detalle de Contenedores de la Recepción';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Componentes de formulario si permites edición directa
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->modifyQueryUsing(function (Builder $query) {
                return $query
                    ->join('contenedores_historial as g', 'contenedores.id', '=', 'g.contenedores_id')
                    ->where('g.estados_contenedores_id', 1)
                    ->select(
                        'contenedores.*',
                        'g.kilos_netos as kilos_netos_historial'
                    );
            })
            ->columns([
                TextColumn::make('id')
                    ->label('ID Contenedor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('recepcion.persona.nombre')
                    ->label('Persona / Proveedor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('producto.nombre')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('variedad.nombre')
                    ->label('Variedad')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('calibre.nombre')
                    ->label('Calibre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kilos_netos_historial')
                    ->label('Kilos Netos')
                    ->alignEnd()
                    ->formatStateUsing(function ($state) {
                        if (!$state) return '0 kg';
                        
                        // Si es entero (ej: 500) omite los decimales
                        if ($state == (int)$state) {
                            return number_format($state, 0, ',', '.') . ' kg';
                        }
                        
                        // Si es decimal (ej: 500.5), quita los ceros sobrantes
                        return rtrim(number_format($state, 2, ',', '.'), '0') . ' kg';
                    })
                    ->summarize(
                        Sum::make('kilos_netos_historial')
                            ->label('Total Kilos')
                            ->formatStateUsing(function ($state) {
                                if (!$state) return '0 kg';
                                if ($state == (int)$state) return number_format($state, 0, ',', '.') . ' kg';
                                return rtrim(number_format($state, 2, ',', '.'), '0') . ' kg';
                            })
                    )
                    ->sortable(),
            ]);
    }
}