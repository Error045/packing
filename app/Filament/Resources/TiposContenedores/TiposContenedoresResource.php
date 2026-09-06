<?php

namespace App\Filament\Resources\TiposContenedores;

use App\Filament\Resources\TiposContenedores\Pages\CreateTiposContenedores;
use App\Filament\Resources\TiposContenedores\Pages\EditTiposContenedores;
use App\Filament\Resources\TiposContenedores\Pages\ListTiposContenedores;
use App\Filament\Resources\TiposContenedores\Pages\ViewTiposContenedores;
use App\Filament\Resources\TiposContenedores\Schemas\TiposContenedoresForm;
use App\Filament\Resources\TiposContenedores\Schemas\TiposContenedoresInfolist;
use App\Filament\Resources\TiposContenedores\Tables\TiposContenedoresTable;
use App\Models\TiposContenedores;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TiposContenedoresResource extends Resource
{
    protected static ?string $model = TiposContenedores::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tipos de Contenedores';

    protected static ?string $navigationLabel = 'Tipos de Contenedores';
    protected static ?string $modelLabel = 'Tipo de Contenedor';
    protected static ?string $pluralModelLabel = 'Tipos de Contenedores';

    public static function form(Schema $schema): Schema
    {
        return TiposContenedoresForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TiposContenedoresInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiposContenedoresTable::configure($table);
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
            'index' => ListTiposContenedores::route('/'),
            'create' => CreateTiposContenedores::route('/create'),
            'view' => ViewTiposContenedores::route('/{record}'),
            'edit' => EditTiposContenedores::route('/{record}/edit'),
        ];
    }
}
