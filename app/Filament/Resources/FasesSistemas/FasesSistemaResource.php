<?php

namespace App\Filament\Resources\FasesSistemas;

use App\Filament\Resources\FasesSistemas\Pages\CreateFasesSistema;
use App\Filament\Resources\FasesSistemas\Pages\EditFasesSistema;
use App\Filament\Resources\FasesSistemas\Pages\ListFasesSistemas;
use App\Filament\Resources\FasesSistemas\Pages\ViewFasesSistema;
use App\Filament\Resources\FasesSistemas\Schemas\FasesSistemaForm;
use App\Filament\Resources\FasesSistemas\Schemas\FasesSistemaInfolist;
use App\Filament\Resources\FasesSistemas\Tables\FasesSistemasTable;
use App\Models\FasesSistema;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FasesSistemaResource extends Resource
{
    protected static ?string $model = FasesSistema::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Fases de Sistema';

    public static function form(Schema $schema): Schema
    {
        return FasesSistemaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FasesSistemaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FasesSistemasTable::configure($table);
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
            'index' => ListFasesSistemas::route('/'),
            'create' => CreateFasesSistema::route('/create'),
            'view' => ViewFasesSistema::route('/{record}'),
            'edit' => EditFasesSistema::route('/{record}/edit'),
        ];
    }
}
