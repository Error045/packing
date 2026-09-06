<?php

namespace App\Filament\Resources\Recepcions\Widgets;

use App\Models\Contenedor;
use App\Models\Recepcion;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Support\Facades\DB;

class DetalleValorizacionWidget extends BaseWidget
{
    // Recibe el registro actual de la Recepción
    public ?Recepcion $record = null;

    // Ocupa todo el ancho de la pantalla
    protected int | string | array $columnSpan = 'full';

    protected static ?string $heading = 'Resumen de Valorización';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Contenedor::query()
                    ->join('contenedores_historial as g', 'contenedores.id', '=', 'g.contenedores_id')
                    ->join('productos as c', 'contenedores.productos_id', '=', 'c.id')
                    ->join('variedades as d', 'contenedores.variedades_id', '=', 'd.id')
                    ->join('calibres as e', 'contenedores.calibres_id', '=', 'e.id')
                    ->join('productos_precios as h', 'contenedores.id', '=', 'h.contenedores_id')
                    ->select([
                        DB::raw('MIN(contenedores.id) as id'),
                        'c.nombre as producto',
                        'd.nombre as variedad',
                        'e.nombre as calibre',
                        'contenedores.calibres_id',
                        DB::raw('SUM(g.kilos_netos) as netos'),
                        'h.precio',
                        DB::raw('SUM(g.kilos_netos * h.precio) as total'),
                    ])
                    ->where('contenedores.recepciones_id', $this->record?->id)
                    ->where('g.estados_contenedores_id', 5)
                    ->groupBy('c.nombre', 'd.nombre', 'e.nombre', 'contenedores.calibres_id', 'h.precio')
                    ->orderBy('contenedores.calibres_id', 'asc')
            )
            ->columns([
                TextColumn::make('producto')->label('Producto'),
                TextColumn::make('variedad')->label('Variedad'),
                TextColumn::make('calibre')->label('Calibre')->badge()->color('success'),
                TextColumn::make('netos')
                    ->label('Kilos Netos')
                    ->numeric(decimalPlaces: 0, decimalSeparator: ',', thousandsSeparator: '.')
                    ->suffix(' kg')
                    ->alignEnd()
                    ->summarize(
                        Sum::make()
                            ->label('Total Kilos')
                            ->numeric(decimalPlaces: 0, decimalSeparator: ',', thousandsSeparator: '.')
                            ->suffix(' kg')
                    ),
                TextColumn::make('precio')->label('Precio Unitario')->money('CLP')->alignEnd(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('CLP')
                    ->weight('bold')
                    ->alignEnd()
                    ->summarize([
                        Summarizer::make('subtotal_neto')
                            ->label('Subtotal Neto')
                            ->using(fn ($query) => $query->get()->sum('total'))
                            ->money('CLP'),
                        Summarizer::make('iva')
                            ->label('IVA (19%)')
                            ->using(fn ($query) => $query->get()->sum('total') * 0.19)
                            ->money('CLP'),
                        Summarizer::make('total_general')
                            ->label('Total General (Neto + IVA)')
                            ->using(fn ($query) => $query->get()->sum('total') * 1.19)
                            ->money('CLP'),
                    ]),
            ])
            ->paginated(false);
    }
}