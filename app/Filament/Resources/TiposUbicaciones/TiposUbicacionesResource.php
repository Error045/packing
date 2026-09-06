<?php

namespace App\Filament\Resources\TiposUbicaciones;

use App\Filament\Resources\TiposUbicaciones\Pages\CreateTiposUbicaciones;
use App\Filament\Resources\TiposUbicaciones\Pages\EditTiposUbicaciones;
use App\Filament\Resources\TiposUbicaciones\Pages\ListTiposUbicaciones;
use App\Filament\Resources\TiposUbicaciones\Pages\ViewTiposUbicaciones;
use App\Filament\Resources\TiposUbicaciones\Schemas\TiposUbicacionesForm;
use App\Filament\Resources\TiposUbicaciones\Schemas\TiposUbicacionesInfolist;
use App\Filament\Resources\TiposUbicaciones\Tables\TiposUbicacionesTable;
use App\Models\TiposUbicaciones;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TiposUbicacionesResource extends Resource
{
    protected static ?string $model = TiposUbicaciones::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tipos Ubicaciones';

    protected static ?string $navigationLabel = 'Tipos de Ubicaciones';
    protected static ?string $modelLabel = 'Tipo de Ubicacion';
    protected static ?string $pluralModelLabel = 'Tipos de Ubicaciones';

    public static function form(Schema $schema): Schema
    {
        return TiposUbicacionesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TiposUbicacionesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiposUbicacionesTable::configure($table);
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
            'index' => ListTiposUbicaciones::route('/'),
            'create' => CreateTiposUbicaciones::route('/create'),
            'view' => ViewTiposUbicaciones::route('/{record}'),
            'edit' => EditTiposUbicaciones::route('/{record}/edit'),
        ];
    }
}
