<?php

namespace App\Filament\Resources\Recepcions\Pages;

use App\Models\Contenedor;
use App\Filament\Resources\Recepcions\RecepcionResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

// IMPORTANTE: Asegúrate de importar desde Infolists, NO desde Forms
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Support\Facades\DB;

class ResumenValorizacion extends Page implements HasInfolists, HasTable
{
    use InteractsWithRecord;
    use InteractsWithInfolists;
    use InteractsWithTable;

    protected static string $resource = RecepcionResource::class;

    protected string $view = 'filament.resources.recepcions.pages.resumen-valorizacion';

    protected static ?string $title = 'Resumen de Valorización';

    public function mount(int | string $record): void
    {
      //  $this->record = $this->resolveRecord($record);
      // Cargamos el registro y sus relaciones explícitamente
        $this->record = $this->resolveRecord($record)->load(['persona', 'TiposRecepciones', 'proceso']);
    }

  

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
                    ->where('contenedores.recepciones_id', $this->record->id)
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
    ->summarize([
        // 1. Este quedará alineado a la altura del "Subtotal Neto"
        Sum::make('total_kilos')
             ->label('Total kilos')
                    ->formatStateUsing(function ($state) {
            if (!$state) return '0 kg';
        
            // Si el número es entero (ej: 430.00 -> 430)
            if ($state == (int)$state) {
            return number_format($state, 0, ',', '.') . ' kg';
        }
        
        // Si tiene decimales, formateamos a 2 y le quitamos los ceros sobrantes a la derecha (ej: 500.50 -> 500,5)
        return rtrim(number_format($state, 2, ',', '.'), '0') . ' kg';
        }), 

        
        // 2. Bloque invisible para alinear con la altura del "IVA"
        Summarizer::make('espacio_iva')
            ->label('')
            ->using(fn () => new \Illuminate\Support\HtmlString('&nbsp;')),
            
        // 3. Bloque invisible para alinear con la altura del "Total General"
        Summarizer::make('espacio_total')
            ->label('')
            ->using(fn () => new \Illuminate\Support\HtmlString('&nbsp;')),
    ]),
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