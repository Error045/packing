<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Resumen de Valorización - Recepción #{{ $recepcion->id }}</title>
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

        .total-row.grand {
            background-color: #e5e7eb;
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
            🖨️ Imprimir / Guardar PDF
        </button>
    </div>

    <div class="header">
        <div>
            <h2>RESUMEN DE VALORIZACIÓN</h2>
            <p><strong>N° Recepción:</strong> #{{ $recepcion->id }}</p>
        </div>
        <div>
            <p><strong>Fecha Impresión:</strong> {{ now()->format('d/m/Y H:i') }}</p>
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

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Variedad</th>
                <th>Calibre</th>
                <th class="text-right">Kilos Netos</th>
                <th class="text-right">Precio Unitario</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($detalle as $item)
                <tr>
                    <td>{{ $item->producto }}</td>
                    <td>{{ $item->variedad }}</td>
                    <td>{{ $item->calibre }}</td>
                    <td class="text-right">
                        {{ $item->netos == (int) $item->netos ? number_format($item->netos, 0, ',', '.') : rtrim(number_format($item->netos, 2, ',', '.'), '0') }}
                        kg
                    </td>
                    <td class="text-right">${{ number_format($item->precio, 0, ',', '.') }}</td>
                    <td class="text-right">${{ number_format($item->total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Sin datos de valorización registrados.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Total Kilos:</td>
                <td class="text-right">
                    {{ $totalKilos == (int) $totalKilos ? number_format($totalKilos, 0, ',', '.') : rtrim(number_format($totalKilos, 2, ',', '.'), '0') }}
                    kg
                </td>
                <td colspan="2"></td>
            </tr>
            <tr class="total-row">
                <td colspan="5" class="text-right">Subtotal Neto:</td>
                <td class="text-right">${{ number_format($subtotalNeto, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="5" class="text-right">IVA (19%):</td>
                <td class="text-right">${{ number_format($iva, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row grand">
                <td colspan="5" class="text-right">Total General (Neto + IVA):</td>
                <td class="text-right">${{ number_format($totalGeneral, 0, ',', '.') }}</td>
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
