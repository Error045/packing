<?php

namespace App\Filament\Resources\Variedads;

use App\Filament\Resources\Variedads\Pages\CreateVariedad;
use App\Filament\Resources\Variedads\Pages\EditVariedad;
use App\Filament\Resources\Variedads\Pages\ListVariedads;
use App\Filament\Resources\Variedads\Pages\ViewVariedad;
use App\Filament\Resources\Variedads\Schemas\VariedadForm;
use App\Filament\Resources\Variedads\Schemas\VariedadInfolist;
use App\Filament\Resources\Variedads\Tables\VariedadsTable;
use App\Models\Variedad;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VariedadResource extends Resource
{
    protected static ?string $model = Variedad::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Variedades';

    protected static ?string $navigationLabel = 'Variedades';
    protected static ?string $modelLabel = 'variedad';
    protected static ?string $pluralModelLabel = 'variedades';

    public static function form(Schema $schema): Schema
    {
        return VariedadForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VariedadInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VariedadsTable::configure($table);
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
            'index' => ListVariedads::route('/'),
            'create' => CreateVariedad::route('/create'),
            'view' => ViewVariedad::route('/{record}'),
            'edit' => EditVariedad::route('/{record}/edit'),
        ];
    }
}
