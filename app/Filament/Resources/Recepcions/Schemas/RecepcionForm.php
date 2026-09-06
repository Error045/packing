<?php

namespace App\Filament\Resources\Recepcions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class RecepcionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
               // 1. Tipo de Recepción
                Select::make('tipos_recepciones_id')
                    ->label('Tipo de Recepción')
                    ->relationship('TiposRecepciones', 'tipo') // Busca la relación en el modelo Recepcion y muestra el campo 'tipo'
                    ->preload() // Carga la lista al abrir la página
                    ->required(),  
              /*  TextInput::make('tipos_recepciones_id')  //
                    ->required()
                    ->numeric(), */
                // 2. Productor (Persona)
                Select::make('personas_id')
                    ->label('Productor / Persona')
                    ->relationship('persona', 'nombre') // Muestra el 'nombre'
                    ->searchable() // Permite buscar escribiendo (útil si hay muchos)
                    ->preload()
                    ->required(),
                /*    TextInput::make('personas_id')
                    ->required()
                    ->numeric(), */

                // 3. Fecha
                DatePicker::make('fecha')
                    ->default(now())
                    ->required(),

                // 4. Hora
                TimePicker::make('hora')
                    ->default(now())
                    ->required(),

                // 5. Estado de la Recepción
                Select::make('estados_recepciones_id')
                    ->label('Estado de Recepción')
                    // Asumiendo que tu relación se llama estadoRecepcion en el modelo Recepcion
                    ->relationship('estadoRecepcion', 'nombre') 
                    ->default(1) // Por defecto estado inicial
                    ->required(),    

              /*  TextInput::make('estados_recepciones_id')
                    ->required()
                    ->numeric(), */

                // 6. Usuario que registra (Oculto o automático)
                // Es mejor guardarlo automáticamente sin que el usuario tenga que escribir su ID
                TextInput::make('users_id')
                    ->label('ID Usuario')
                    ->default(fn () => Auth::id()) // Rellena con el ID del usuario logueado
                    ->readOnly() // Evita que lo editen
                    ->required()
                    ->numeric()
                    ->hiddenOn('edit'), // Opcional: ocúltalo al editar para que no estorbe    
              /*  TextInput::make('users_id')
                    ->required()
                    ->numeric(), */
                // 7. Estado lógico (Activo/Inactivo)
                Toggle::make('estado')
                    ->default(true),
            ]);
    }
}
