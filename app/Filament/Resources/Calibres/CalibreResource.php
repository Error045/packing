<?php

namespace App\Filament\Resources\Calibres;

use App\Filament\Resources\Calibres\Pages\CreateCalibre;
use App\Filament\Resources\Calibres\Pages\EditCalibre;
use App\Filament\Resources\Calibres\Pages\ListCalibres;
use App\Filament\Resources\Calibres\Pages\ViewCalibre;
use App\Filament\Resources\Calibres\Schemas\CalibreForm;
use App\Filament\Resources\Calibres\Schemas\CalibreInfolist;
use App\Filament\Resources\Calibres\Tables\CalibresTable;
use App\Models\Calibre;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CalibreResource extends Resource
{
    protected static ?string $model = Calibre::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Calibre';

    public static function form(Schema $schema): Schema
    {
        return CalibreForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CalibreInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CalibresTable::configure($table);
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
            'index' => ListCalibres::route('/'),
            'create' => CreateCalibre::route('/create'),
            'view' => ViewCalibre::route('/{record}'),
            'edit' => EditCalibre::route('/{record}/edit'),
        ];
    }
}
