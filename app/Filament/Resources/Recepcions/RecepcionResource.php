<?php

namespace App\Filament\Resources\Recepcions;

// 1. AGREGA ESTAS IMPORTACIONES EN EL ENCABEZADO:
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;

use App\Filament\Resources\Recepcions\Pages\CreateRecepcion;
use App\Filament\Resources\Recepcions\Pages\EditRecepcion;
use App\Filament\Resources\Recepcions\Pages\ListRecepcions;
use App\Filament\Resources\Recepcions\Pages\ViewRecepcion;
use App\Filament\Resources\Recepcions\Schemas\RecepcionForm;
use App\Filament\Resources\Recepcions\Schemas\RecepcionInfolist;
use App\Filament\Resources\Recepcions\Tables\RecepcionsTable;
use App\Filament\Resources\Recepcions\RelationManagers\ContenedoresRelationManager;
use App\Models\Recepcion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RecepcionResource extends Resource
{
    protected static ?string $model = Recepcion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RecepcionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RecepcionsTable::configure($table);
    }

  public static function getRelations(): array
    {
        return [
            // 🟢 2. REGISTRA EL RELATION MANAGER AQUÍ
            ContenedoresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
        'index' => Pages\ListRecepcions::route('/'),
        'create' => Pages\CreateRecepcion::route('/create'),
        'edit' => Pages\EditRecepcion::route('/{record}/edit'),
        
        // Nuevas páginas registradas:
        'detalle' => Pages\ViewDetalleRecepcion::route('/{record}/detalle'),
        'calibrado' => Pages\GestionarCalibrado::route('/{record}/calibrado'),
        'precios' => Pages\AsignarPrecios::route('/{record}/precios'),
        'valorizacion' => Pages\ResumenValorizacion::route('/{record}/valorizacion'), 
        'resumen-valorizacion' => Pages\ResumenValorizacion::route('/{record}/resumen-valorizacion'),
        
        ];

    }
}
