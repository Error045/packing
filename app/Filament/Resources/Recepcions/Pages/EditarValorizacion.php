<?php

namespace App\Filament\Resources\Recepcions\Pages;

use App\Filament\Resources\Recepcions\RecepcionResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class EditarValorizacion extends Page implements HasForms
{
    use InteractsWithRecord, InteractsWithForms;

    protected static string $resource = RecepcionResource::class;

    protected string $view =
    'filament.resources.recepcions.pages.editar-valorizacion';

    protected static ?string $title = 'Editar Valorización';

    public ?array $data = [];

    public function mount(int|string $record): void
    {
        $this->record = static::getResource()
            ::getEloquentQuery()
            ->findOrFail($record);

        $calibresAgrupados = DB::table('contenedores as a')
            ->join(
                'contenedores_historial as g',
                'a.id',
                '=',
                'g.contenedores_id'
            )
            ->join(
                'productos as c',
                'a.productos_id',
                '=',
                'c.id'
            )
            ->join(
                'variedades as d',
                'a.variedades_id',
                '=',
                'd.id'
            )
            ->join(
                'calibres as e',
                'a.calibres_id',
                '=',
                'e.id'
            )
            ->leftJoin(
                'productos_precios as h',
                'h.contenedores_id',
                '=',
                'a.id'
            )
            ->where('a.recepciones_id', $this->record->id)
            ->where('g.estados_contenedores_id', 5)
            ->groupBy(
                'a.calibres_id',
                'e.nombre',
                'c.nombre',
                'd.nombre'
            )
            ->select(
                'a.calibres_id',
                'e.nombre as calibre',
                'c.nombre as producto',
                'd.nombre as variedad',
                DB::raw('SUM(g.kilos_netos) as netos'),
                DB::raw('COUNT(a.id) as total_bines'),

                // Recuperar los valores actualmente asignados
                DB::raw('MIN(h.precio) as precio'),
                DB::raw('MIN(h.id_xof) as id_xof')
            )
            ->orderBy('a.calibres_id', 'asc')
            ->get();

        $this->form->fill([
            'detalle_precios' => $calibresAgrupados
                ->map(function ($item) {
                    return [
                        'calibres_id' => $item->calibres_id,
                        'calibre' => $item->calibre,
                        'producto_variedad' =>
                        "{$item->producto} - {$item->variedad}",
                        'kilos_netos' => $item->netos,
                        'total_bines' => $item->total_bines,

                        // Valores existentes
                        'precio' => $item->precio,
                        'id_xof' => $item->id_xof,
                    ];
                })
                ->toArray(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Repeater::make('detalle_precios')
                    ->label('Calibres a Valorizar')
                    ->schema([
                        Hidden::make('calibres_id'),

                        TextInput::make('calibre')
                            ->label('Calibre')
                            ->disabled(),

                        TextInput::make('total_bines')
                            ->label('Total Bines')
                            ->disabled(),

                        TextInput::make('kilos_netos')
                            ->label('Kilos Netos')
                            ->disabled(),

                        TextInput::make('precio')
                            ->label('Precio')
                            ->required()
                            ->numeric(),

                        Select::make('id_xof')
                            ->label('XOF')
                            ->options(fn() => DB::table('xof')
                                ->where('estado', 1)
                                ->pluck('nombre', 'id'))
                            ->searchable()
                            ->native(false)
                            ->placeholder('Seleccionar...')
                            ->required(),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->columns(5),
            ])
            ->statePath('data');
    }

    public function guardar(): void
    {
        $data = $this->form->getState();

        DB::transaction(function () use ($data) {

            foreach ($data['detalle_precios'] as $item) {

                // Obtener todos los contenedores pertenecientes
                // al calibre que se está editando.
                $contenedoresIds = DB::table('contenedores as a')
                    ->join(
                        'contenedores_historial as g',
                        'a.id',
                        '=',
                        'g.contenedores_id'
                    )
                    ->where('a.recepciones_id', $this->record->id)
                    ->where('a.calibres_id', $item['calibres_id'])
                    ->where('g.estados_contenedores_id', 5)
                    ->pluck('a.id');

                // Aplicar el nuevo precio y XOF
                // a todos los contenedores del calibre.
                foreach ($contenedoresIds as $contenedorId) {

                    DB::table('productos_precios')
                        ->where('recepciones_id', $this->record->id)
                        ->where('contenedores_id', $contenedorId)
                        ->update([
                            'precio' => $item['precio'],
                            'id_xof' => $item['id_xof'],
                            'updated_at' => now(),
                            'fecha' => now(),
                        ]);
                }
            }
        });

        Notification::make()
            ->title('Valorización actualizada correctamente')
            ->success()
            ->send();

        $this->redirect(
            RecepcionResource::getUrl('index')
        );
    }
}
