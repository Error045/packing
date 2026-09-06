<?php

namespace App\Filament\Resources\Recepcions\Pages;

use App\Models\Contenedor;
use App\Filament\Resources\Recepcions\RecepcionResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Support\Facades\DB;

class GestionarCalibrado extends Page implements HasTable
{
    use InteractsWithRecord;
    use InteractsWithTable; // <-- Agregamos el soporte para tablas

    protected static string $resource = RecepcionResource::class;
    
    protected string $view = 'filament.resources.recepcions.pages.gestionar-calibrado'; 
    
    protected static ?string $title = 'Detalle de Calibrado';

    public float $totalKilosGeneral = 0;

    public function mount(int | string $record): void
    {
        // Cargamos el registro y sus relaciones para la cabecera
        $this->record = $this->resolveRecord($record)->load(['persona', 'TiposRecepciones', 'proceso']);

        // Calculamos el gran total antes de armar la tabla para poder sacar el porcentaje
        $this->totalKilosGeneral = DB::table('contenedores as a')
            ->join('contenedores_historial as g', 'a.id', '=', 'g.contenedores_id')
            ->where('a.recepciones_id', $this->record->id)
            ->where('g.estados_contenedores_id', 5)
            ->sum('g.kilos_netos');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Contenedor::query()
                    // 1. Quitamos el alias "a" y usamos "contenedores"
                    ->join('contenedores_historial as g', 'contenedores.id', '=', 'g.contenedores_id')
                    ->join('calibres as e', 'contenedores.calibres_id', '=', 'e.id')
                    ->select([
                        DB::raw('MIN(contenedores.id) as id'), // Filament necesita esto
                        'e.nombre as calibre',
                        DB::raw('SUM(g.kilos_netos) as netos'),
                    ])
                    ->where('contenedores.recepciones_id', $this->record->id)
                    ->where('g.estados_contenedores_id', 5)
                    ->groupBy('e.nombre')
                    ->orderBy('e.id')  // Ordenar por calibre
            )
           // ->defaultSort('calibre', 'desc') // 2. Le decimos a Filament que ordene desde aquí
            ->columns([
                TextColumn::make('calibre')
                    ->label('Calibre')
                    ->badge() 
                    ->color('info')
                    ->sortable(), // Permite al usuario ordenar haciendo clic
                
                TextColumn::make('netos')
                    ->label('Kilos Netos')
                    ->formatStateUsing(function ($state) {
        if (!$state) return '0 kg';
        
        // Si el número es entero (ej: 430.00 -> 430)
        if ($state == (int)$state) {
            return number_format($state, 0, ',', '.') . ' kg';
        }
        
        // Si tiene decimales, formateamos a 2 y le quitamos los ceros sobrantes a la derecha (ej: 500.50 -> 500,5)
        return rtrim(number_format($state, 2, ',', '.'), '0') . ' kg';
        }) 
                    ->alignEnd()
                    ->summarize(
            Sum::make()
            ->label('Total Kilos')
            ->formatStateUsing(function ($state) {
                if (!$state) return '0 kg';
                if ($state == (int)$state) return number_format($state, 0, ',', '.') . ' kg';
                return rtrim(number_format($state, 2, ',', '.'), '0') . ' kg';
            })

                    ),

                TextColumn::make('porcentaje')
                    ->label('Porcentaje (%)')
                    ->state(function ($record) {
                        return $this->totalKilosGeneral > 0 
                            ? ($record->netos / $this->totalKilosGeneral) * 100 
                            : 0;
                    })
                    ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.')
                    ->suffix(' %')
                    ->alignEnd()
                    ->weight('bold')
                    ->summarize(
                        Summarizer::make('total_porc')
                            ->label('Suma')
                            ->using(fn () => 100)
                            ->numeric(decimalPlaces: 2, decimalSeparator: ',', thousandsSeparator: '.')
                            ->suffix(' %')
                    ),
            ])
            ->paginated(false);
    }
}