<?php

namespace App\Filament\Resources\TiposRecepciones;

use App\Filament\Resources\TiposRecepciones\Pages\CreateTiposRecepciones;
use App\Filament\Resources\TiposRecepciones\Pages\EditTiposRecepciones;
use App\Filament\Resources\TiposRecepciones\Pages\ListTiposRecepciones;
use App\Filament\Resources\TiposRecepciones\Pages\ViewTiposRecepciones;
use App\Filament\Resources\TiposRecepciones\Schemas\TiposRecepcionesForm;
use App\Filament\Resources\TiposRecepciones\Schemas\TiposRecepcionesInfolist;
use App\Filament\Resources\TiposRecepciones\Tables\TiposRecepcionesTable;
use App\Models\TiposRecepciones;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TiposRecepcionesResource extends Resource
{
    protected static ?string $model = TiposRecepciones::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Tipos Recepciones';

    public static function form(Schema $schema): Schema
    {
        return TiposRecepcionesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TiposRecepcionesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiposRecepcionesTable::configure($table);
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
            'index' => ListTiposRecepciones::route('/'),
            'create' => CreateTiposRecepciones::route('/create'),
            'view' => ViewTiposRecepciones::route('/{record}'),
            'edit' => EditTiposRecepciones::route('/{record}/edit'),
        ];
    }
}
