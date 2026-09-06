<x-filament-panels::page>
    <div class="bg-white p-6 rounded-lg shadow border border-gray-300">
        <h2 class="text-xl font-bold mb-4 text-red-600">Diagnóstico de Datos Recibidos</h2>
        
        <ul class="space-y-3 text-lg mb-8">
            <li><strong>ID Recepción:</strong> {{ $this->record->id }}</li>
            <li><strong>Fecha:</strong> {{ $this->record->fecha }}</li>
            
            {{-- Usamos ?? para mostrar un mensaje si el dato viene nulo --}}
            <li>
                <strong>Productor (Relación Persona):</strong> 
                <span class="text-blue-600">{{ $this->record->persona->nombre ?? '❌ NULO O NO ENCONTRADO' }}</span>
            </li>
            
            <li>
                <strong>Tipo Recepción:</strong> 
                <span class="text-blue-600">{{ $this->record->TiposRecepciones->tipo ?? '❌ NULO O NO ENCONTRADO' }}</span>
            </li>
            
            <li>
                <strong>Proceso ID:</strong> 
                <span class="text-blue-600">{{ $this->record->proceso->id ?? '❌ NULO O NO ENCONTRADO' }}</span>
            </li>
        </ul>

        <hr>

        <div class="mt-6">
            <h3 class="font-bold text-gray-700 mb-2">Visor de datos en crudo (JSON):</h3>
            <p class="text-sm text-gray-500 mb-2">Si los campos de arriba dicen "NULO", busca aquí abajo los nombres reales de las columnas que está trayendo tu base de datos.</p>
            <pre class="bg-gray-900 text-green-400 p-4 rounded text-xs overflow-x-auto">
                @php
                    echo json_encode($this->record->toArray(), JSON_PRETTY_PRINT);
                @endphp
            </pre>
        </div>
    </div>
</x-filament-panels::page>