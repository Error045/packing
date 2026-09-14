<?php

namespace App\Filament\Resources\Recepcions\Pages;

use App\Filament\Resources\Recepcions\RecepcionResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord; // 👈 1. Asegúrate de incluir esto
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class AsignarPrecios extends Page implements HasForms
{
    use InteractsWithRecord, InteractsWithForms; // 👈 2. Y usarlo aquí

    protected static string $resource = RecepcionResource::class;

    protected string $view = 'filament.resources.recepcions.pages.asignar-precios';

    protected static ?string $title = 'Asignar Precios de Recepción';

    public ?array $data = [];

    private const XOF_ID_DEFAULT = 1;

    public function mount(int | string $record): void
    {
        $this->record = static::getResource()::getEloquentQuery()->findOrFail($record);

        // 3. Consulta SQL completa con todos los joins necesarios
        $calibresAgrupados = DB::table('contenedores as a')
            ->join('contenedores_historial as g', 'a.id', '=', 'g.contenedores_id')
            ->join('productos as c', 'a.productos_id', '=', 'c.id')
            ->join('variedades as d', 'a.variedades_id', '=', 'd.id')
            ->join('calibres as e', 'a.calibres_id', '=', 'e.id')
            ->leftJoin('productos_precios as h', 'h.contenedores_id', '=', 'a.id')
            ->where('a.recepciones_id', $this->record->id)
            ->where('g.estados_contenedores_id', 5)
            ->groupBy('a.calibres_id', 'e.nombre', 'c.nombre', 'd.nombre')
            ->select(
                'a.calibres_id',
                'e.nombre as calibre',
                'c.nombre as producto',
                'd.nombre as variedad',
                DB::raw('SUM(g.kilos_netos) as netos'),
                DB::raw('COUNT(a.id) as total_bines'),
                DB::raw('MIN(h.id_xof) as id_xof')
            )
            ->orderBy('a.calibres_id', 'asc')
            ->get();

        // Llenar el formulario
        $this->form->fill([
            'detalle_precios' => $calibresAgrupados->map(function ($item) {
                return [
                    'calibres_id' => $item->calibres_id,
                    'calibre' => $item->calibre,
                    'producto_variedad' => "{$item->producto} - {$item->variedad}",
                    'kilos_netos' => $item->netos,
                    'total_bines' => $item->total_bines,
                    'precio' => 0,
                    'id_xof' => self::XOF_ID_DEFAULT,
                ];
            })->toArray(),
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
                        TextInput::make('calibre')->disabled(),
                        TextInput::make('total_bines')->disabled(),
                        TextInput::make('kilos_netos')->disabled(),
                        TextInput::make('precio')->required()->numeric(),
                        Select::make('id_xof')
                            ->label('XOF')
                            ->options(fn() => DB::table('xof')
                                ->where('estado', 1)
                                ->pluck('nombre', 'id'))
                            ->searchable()
                            ->native(false)
                            ->placeholder('Seleccionar...'),
                    ])
                    ->addable(false)
                    ->deletable(false)
                    ->columns(5),
            ])
            ->statePath('data');
    }

    public function guardar()
    {
        $data = $this->form->getState();

        // 4. Lógica de guardado masiva dentro de la transacción
        DB::transaction(function () use ($data) {
            foreach ($data['detalle_precios'] as $item) {
                $contenedoresIds = DB::table('contenedores as a')
                    ->join('contenedores_historial as g', 'a.id', '=', 'g.contenedores_id')
                    ->where('a.recepciones_id', $this->record->id)
                    ->where('a.calibres_id', $item['calibres_id'])
                    ->where('g.estados_contenedores_id', 5)
                    ->pluck('a.id');

                foreach ($contenedoresIds as $contenedorId) {
                    DB::table('productos_precios')->updateOrInsert(
                        [
                            'recepciones_id' => $this->record->id,
                            'contenedores_id' => $contenedorId,
                        ],
                        [
                            'precio' => $item['precio'],
                            'id_xof' => $item['id_xof'],
                            'estado' => 1,
                            'fecha' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        });

        Notification::make()
            ->title('Precios guardados correctamente')
            ->success()
            ->send();

        // Volver a la tabla principal
        $this->redirect(RecepcionResource::getUrl('index'));
    }
}
