<?php

namespace App\Filament\Resources\EstadosContenedores;

use App\Filament\Resources\EstadosContenedores\Pages\CreateEstadosContenedores;
use App\Filament\Resources\EstadosContenedores\Pages\EditEstadosContenedores;
use App\Filament\Resources\EstadosContenedores\Pages\ListEstadosContenedores;
use App\Filament\Resources\EstadosContenedores\Pages\ViewEstadosContenedores;
use App\Filament\Resources\EstadosContenedores\Schemas\EstadosContenedoresForm;
use App\Filament\Resources\EstadosContenedores\Schemas\EstadosContenedoresInfolist;
use App\Filament\Resources\EstadosContenedores\Tables\EstadosContenedoresTable;
use App\Models\EstadosContenedores;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EstadosContenedoresResource extends Resource
{
    protected static ?string $model = EstadosContenedores::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Estados Contenedor';

    public static function form(Schema $schema): Schema
    {
        return EstadosContenedoresForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EstadosContenedoresInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstadosContenedoresTable::configure($table);
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
            'index' => ListEstadosContenedores::route('/'),
            'create' => CreateEstadosContenedores::route('/create'),
            'view' => ViewEstadosContenedores::route('/{record}'),
            'edit' => EditEstadosContenedores::route('/{record}/edit'),
        ];
    }
}
