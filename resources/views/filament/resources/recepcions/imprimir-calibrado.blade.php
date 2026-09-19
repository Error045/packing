<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Informe de Calibrado - Recepción #{{ $recepcion->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 15px 0;
        }

        .info-grid span {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: bold;
            opacity: .7;
        }

        .info-grid p {
            margin: 2px 0 0;
            font-size: 1.1rem;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: bold;
            background-color: #fafafa;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()"
            style="padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">
            🖨️ Imprimir
        </button>
    </div>

    <div class="header items-center" style="text-transform: uppercase;">
        <div class="row">
            <div class="justify-between items-center mb-6 border-b-2 border-gray-800 pb-4">
                <div>
                    {{-- <h1 style="font-size: 2rem; font-weight: 900; margin: 0; text-transform: uppercase;">El Chejo</h1>
                    <p style="font-size: 1rem; color: #333; margin: 0;">RUT: 76.XXX.XXX-X<br>Casa Matriz: Ruta e-35
                        Sitio
                        N°
                        7,La Higuera La Ligua</p> --}}
                    <img src="{{ asset('images/logo.webp') }}" alt="Logo"
                        style="width: 200px; height: auto; margin: 0;">
                    <p style="font-size: 1rem; color: #333; margin: 0;">RUT: 76.XXX.XXX-X<br>Casa Matriz: Ruta e-35
                        Sitio
                        N°
                        7,La Higuera La Ligua</p>
                </div>

            </div>
        </div>
    </div>

    <div style="text-transform: uppercase;">
        <div>
            <h2>INFORME DE CALIBRADO</h2>
            <p style="font-size: 0.85rem; margin: 0;"><strong>N° Recepción:</strong> #{{ $recepcion->id }}</p>
        </div>
        <div>
            <p style="font-size: 0.85rem; margin: 0;"><strong>Fecha Impresión:</strong> {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>

    <div class="info-grid">
        <div><span>Productor / Empresa</span>
            <p>{{ $recepcion->persona->nombre ?? 'N/A' }}</p>
        </div>
        <div><span>Tipo</span>
            <p>{{ $recepcion->TiposRecepciones->tipo ?? 'N/A' }}</p>
        </div>
        <div><span>Proceso Calibrado</span>
            <p>#{{ $recepcion->proceso->id ?? 'Pendiente' }}</p>
        </div>
    </div>

    <table style="font-size: 0.8rem; text-transform: uppercase;">
        <thead>
            <tr>
                <th>Calibre</th>
                <th class="text-right">Kilos Netos</th>
                <th class="text-right">Porcentaje (%)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($calibres as $item)
                <tr>
                    <td>{{ $item->calibre }}</td>
                    <td class="text-right">
                        {{ $item->netos == (int) $item->netos ? number_format($item->netos, 0, ',', '.') : rtrim(number_format($item->netos, 2, ',', '.'), '0') }}
                        kg
                    </td>
                    <td class="text-right">{{ number_format($item->porcentaje, 2, ',', '.') }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align:center;">Sin datos de calibrado registrados.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td class="text-right">Total:</td>
                <td class="text-right">
                    {{ $totalKilosGeneral == (int) $totalKilosGeneral ? number_format($totalKilosGeneral, 0, ',', '.') : rtrim(number_format($totalKilosGeneral, 2, ',', '.'), '0') }}
                    kg
                </td>
                <td class="text-right">100%</td>
            </tr>
        </tfoot>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
