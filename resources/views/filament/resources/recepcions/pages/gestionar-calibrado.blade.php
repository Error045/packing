<x-filament-panels::page>
    
    {{-- Botones de acción superior --}}
    <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;" class="print:hidden">
        <x-filament::button color="gray" tag="a" href="{{ $this->getResource()::getUrl('index') }}">
            &larr; Volver a Recepciones
        </x-filament::button>

        <x-filament::button color="primary" icon="heroicon-o-printer" onclick="window.print()">
            Imprimir Informe de Calibrado
        </x-filament::button>
    </div>

    {{-- MEMBRETE PARA IMPRESIÓN (Solo visible al imprimir) --}}
    <div class="hidden print:flex justify-between items-center mb-6 border-b-2 border-gray-800 pb-4" style="display: none;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; text-transform: uppercase;">Tu Empresa Agrícola</h1>
            <p style="font-size: 0.85rem; color: #333; margin: 0;">RUT: 76.XXX.XXX-X<br>Dirección Comercial, Ciudad</p>
        </div>
        <div style="text-align: right;">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin: 0; text-transform: uppercase;">INFORME DE CALIBRADO</h2>
            <p style="font-size: 0.85rem; margin: 0;">Fecha Impresión: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- CABECERA (Componente Nativo de Filament) --}}
    <x-filament::section class="print-section">
        <x-slot name="heading">
            <span class="print:hidden">Resumen de Calibrado</span>
        </x-slot>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 0.5rem;" class="print-grid">
            
            <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold; opacity: 0.7;">Nº Recepción</span>
                <p style="font-size: 1.25rem; font-weight: 900; margin-top: 0.25rem;">#{{ $this->record->id }}</p>
            </div>
            
            <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold; opacity: 0.7;">Productor / Empresa</span>
                <p style="font-size: 1.1rem; font-weight: bold; margin-top: 0.25rem;">{{ $this->record->persona->nombre ?? 'N/A' }}</p>
            </div>
            
            <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold; opacity: 0.7;">Tipo</span>
                <p style="font-size: 1.1rem; font-weight: bold; margin-top: 0.25rem;">{{ $this->record->TiposRecepciones->tipo ?? 'N/A' }}</p>
            </div>
            
            <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold; opacity: 0.7;">Proceso Calibrado</span>
                <p style="font-size: 1.1rem; font-weight: bold; margin-top: 0.25rem;">#{{ $this->record->proceso->id ?? 'Pendiente' }}</p>
            </div>

        </div>
    </x-filament::section>

    {{-- TABLA NATIVA DE FILAMENT --}}
    <div class="print-table-container" style="margin-top: 2rem;">
        {{ $this->table }}
    </div>

    {{-- MAGIA CSS PARA LA IMPRESORA --}}
    <style>
        @media print {
            @page { 
                size: A4;
                margin: 10mm; 
            }
            body { 
                zoom: 70%; 
                background-color: white !important; 
                color: black !important;
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
            .print\:hidden, .fi-sidebar, .fi-topbar, .fi-sidebar-close-overlay { 
                display: none !important; 
            }
            .fi-main { 
                padding: 0 !important; 
                margin: 0 !important; 
                width: 100% !important; 
                max-width: 100% !important; 
            }
            .print\:flex { 
                display: flex !important; 
            }
            .print-section { 
                box-shadow: none !important; 
                border: 1px solid #9ca3af !important; 
                border-radius: 4px !important; 
                padding: 1rem !important;
                background-color: transparent !important;
            }
            table { width: 100% !important; }
            tr { page-break-inside: avoid !important; }
            nav[aria-label="Pagination"] { display: none !important; }
        }
    </style>

</x-filament-panels::page>