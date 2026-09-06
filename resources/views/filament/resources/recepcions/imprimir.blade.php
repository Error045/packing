<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante Recepción #{{ $recepcion->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; color: #333; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; background-color: #fafafa; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 15px;">
        <button onclick="window.print()" style="padding: 8px 16px; background: #2563eb; color: white; border: none; border-radius: 4px; cursor: pointer;">
            🖨️ Imprimir
        </button>
    </div>

    <div class="header">
        <div>
            <h2>COMPROBANTE DE RECEPCIÓN</h2>
            <p><strong>N° Recepción:</strong> #{{ $recepcion->id }}</p>
        </div>
        <div>
            <p><strong>Fecha:</strong> {{ $recepcion->created_at ? $recepcion->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>

    <div class="info-grid">
        <div><strong>Proveedor / Persona:</strong> {{ $recepcion->persona->nombre ?? 'N/A' }}</div>
    </div>

    <h3>Detalle de Contenedores (Recepción Original)</h3>
    <table>
        <thead>
            <tr>
                <th>ID Contenedor</th>
                <th>Producto</th>
                <th>Variedad</th>
                <th>Calibre</th>
                <th class="text-right">Kilos Netos</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKilos = 0; @endphp
            @foreach($contenedores as $item)
                @php $totalKilos += $item->kilos_netos; @endphp
                <tr>
                    <td>{{ $item->contenedor_id }}</td>
                    <td>{{ $item->producto ?? '-' }}</td>
                    <td>{{ $item->variedad ?? '-' }}</td>
                    <td>{{ $item->calibre ?? '-' }}</td>
                    <td class="text-right">
                        {{ $item->kilos_netos == (int)$item->kilos_netos ? number_format($item->kilos_netos, 0, ',', '.') : rtrim(number_format($item->kilos_netos, 2, ',', '.'), '0') }} kg
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Total Kilos Recepcionados:</td>
                <td class="text-right">
                    {{ $totalKilos == (int)$totalKilos ? number_format($totalKilos, 0, ',', '.') : rtrim(number_format($totalKilos, 2, ',', '.'), '0') }} kg
                </td>
            </tr>
        </tfoot>
    </table>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>