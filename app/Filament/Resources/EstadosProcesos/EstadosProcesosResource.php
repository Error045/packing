<?php

namespace App\Filament\Resources\EstadosProcesos;

use App\Filament\Resources\EstadosProcesos\Pages\CreateEstadosProcesos;
use App\Filament\Resources\EstadosProcesos\Pages\EditEstadosProcesos;
use App\Filament\Resources\EstadosProcesos\Pages\ListEstadosProcesos;
use App\Filament\Resources\EstadosProcesos\Pages\ViewEstadosProcesos;
use App\Filament\Resources\EstadosProcesos\Schemas\EstadosProcesosForm;
use App\Filament\Resources\EstadosProcesos\Schemas\EstadosProcesosInfolist;
use App\Filament\Resources\EstadosProcesos\Tables\EstadosProcesosTable;
use App\Models\EstadosProcesos;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EstadosProcesosResource extends Resource
{
    protected static ?string $model = EstadosProcesos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Estados Procesos';

    protected static ?string $navigationLabel = 'Estados Procesos';
    protected static ?string $modelLabel = 'estado proceso';
    protected static ?string $pluralModelLabel = 'Estados Proceso';

    public static function form(Schema $schema): Schema
    {
        return EstadosProcesosForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EstadosProcesosInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstadosProcesosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEstadosProcesos::route('/'),
            'create' => CreateEstadosProcesos::route('/create'),
            'view' => ViewEstadosProcesos::route('/{record}'),
            'edit' => EditEstadosProcesos::route('/{record}/edit'),
        ];
    }
}
