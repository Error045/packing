<?php

namespace App\Filament\Resources\TiposOperaciones;

use App\Filament\Resources\TiposOperaciones\Pages\CreateTiposOperaciones;
use App\Filament\Resources\TiposOperaciones\Pages\EditTiposOperaciones;
use App\Filament\Resources\TiposOperaciones\Pages\ListTiposOperaciones;
use App\Filament\Resources\TiposOperaciones\Pages\ViewTiposOperaciones;
use App\Filament\Resources\TiposOperaciones\Schemas\TiposOperacionesForm;
use App\Filament\Resources\TiposOperaciones\Schemas\TiposOperacionesInfolist;
use App\Filament\Resources\TiposOperaciones\Tables\TiposOperacionesTable;
use App\Models\TiposOperaciones;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TiposOperacionesResource extends Resource
{
    protected static ?string $model = TiposOperaciones::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tipos de Operaciones';

    public static function form(Schema $schema): Schema
    {
        return TiposOperacionesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TiposOperacionesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiposOperacionesTable::configure($table);
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
            'index' => ListTiposOperaciones::route('/'),
            'create' => CreateTiposOperaciones::route('/create'),
            'view' => ViewTiposOperaciones::route('/{record}'),
            'edit' => EditTiposOperaciones::route('/{record}/edit'),
        ];
    }
}
