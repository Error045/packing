<x-filament-panels::page>

    {{-- Botones de acción superior (No se imprimen) --}}
    <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem;" class="print:hidden">
        <x-filament::button color="gray" tag="a" href="{{ $this->getResource()::getUrl('index') }}">
            &larr; Volver a Recepciones
        </x-filament::button>

        <x-filament::button color="primary" icon="heroicon-o-printer" tag="a"
            href="{{ route('recepciones.valorizacion.imprimir', $this->record) }}" target="_blank">
            Imprimir Informe / Guardar PDF
        </x-filament::button>
    </div>

    {{-- MEMBRETE PARA IMPRESIÓN (Solo visible al imprimir) --}}
    <div class="hidden print:flex justify-between items-center mb-6 border-b-2 border-gray-800 pb-4"
        style="display: none;">
        <div>
            {{-- Puedes reemplazar este texto por una etiqueta <img src="/logo.png"> si tienes logo --}}
            <h1 style="font-size: 1.5rem; font-weight: 900; margin: 0; text-transform: uppercase;">El Chejo</h1>
            <p style="font-size: 0.85rem; color: #333; margin: 0;">RUT: 76.XXX.XXX-X<br>Casa Matriz:Ruta e-35 Sitio N°
                7,La Higuera La Ligua</p>
        </div>
        <div style="text-align: right;">
            <h2 style="font-size: 1.25rem; font-weight: bold; margin: 0; text-transform: uppercase;">INFORME DE
                VALORIZACIÓN</h2>
            <p style="font-size: 0.85rem; margin: 0;">Fecha Impresión: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    {{-- CABECERA (Datos de la Recepción) --}}
    <x-filament::section class="print-section">
        <x-slot name="heading">
            <span class="print:hidden">Detalle de Valorización</span>
        </x-slot>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 0.5rem;"
            class="print-grid">

            <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold; opacity: 0.7;">Nº
                    Recepción</span>
                <p style="font-size: 1.25rem; font-weight: 900; margin-top: 0.25rem;">#{{ $this->record->id }}</p>
            </div>

            <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold; opacity: 0.7;">Productor
                    / Empresa</span>
                <p style="font-size: 1.1rem; font-weight: bold; margin-top: 0.25rem;">
                    {{ $this->record->persona->nombre ?? 'N/A' }}</p>
                @if ($this->record->persona->empresa)
                    <p style="font-size: 0.85rem; opacity: 0.8;">{{ $this->record->persona->empresa }}</p>
                @endif
            </div>

            <div>
                <span
                    style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold; opacity: 0.7;">Tipo</span>
                <p style="font-size: 1.1rem; font-weight: bold; margin-top: 0.25rem;">
                    {{ $this->record->TiposRecepciones->tipo ?? 'N/A' }}</p>
            </div>

            <div>
                <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: bold; opacity: 0.7;">Proceso
                    Calibrado</span>
                <p style="font-size: 1.1rem; font-weight: bold; margin-top: 0.25rem;">
                    #{{ $this->record->proceso->id ?? 'Pendiente' }}</p>
                <p style="font-size: 0.85rem; opacity: 0.8;">F. Proceso: {{ $this->record->proceso->fecha ?? '-' }}</p>
            </div>

        </div>
    </x-filament::section>

    {{-- TABLA DE FILAMENT --}}
    <div class="print-table-container" style="margin-top: 2rem;">
        {{ $this->table }}
    </div>

    {{-- MAGIA CSS PARA LA IMPRESORA --}}
    <style>
        @media print {

            /* 1. Limpiar la pantalla y forzar fondos de color (como las badges verdes) */
            body {
                zoom: 70%;
                background-color: white !important;
                color: black !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }


            /* 2. Quitar márgenes feos y URL del navegador */
            @page {
                size: Carta;
                margin: 10mm;
                size: auto;
            }

            /* 3. Ocultar menús de Filament */
            .print\:hidden,
            .fi-sidebar,
            .fi-topbar {
                display: none !important;
            }

            .fi-main {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            /* 4. Mostrar el membrete que armamos */
            .print\:flex {
                display: flex !important;
            }

            /* 5. Ajustar la tarjeta superior para que no parezca un botón */
            .print-section {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                border-radius: 4px !important;
                padding: 1rem !important;
                background-color: transparent !important;
            }

            /* 6. Evitar que las filas de la tabla se corten por la mitad si hay varias hojas */
            tr {
                page-break-inside: avoid !important;
            }

            /* 7. Eliminar paginación visual si es que existe */
            nav[aria-label="Pagination"] {
                display: none !important;
            }
        }
    </style>
</x-filament-panels::page>
