<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Comprobante Recepción #{{ $recepcion->id }}</title>
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
            grid-template-columns: 1fr 1fr;
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
            <h2>COMPROBANTE DE RECEPCIÓN</h2>
            <p style="font-size: 0.85rem; margin: 0;"><strong>N° Recepción:</strong> #{{ $recepcion->id }}</p>
        </div>
        <div>
            <p style="font-size: 0.85rem; margin: 0;"><strong>Fecha:</strong>
                {{ $recepcion->created_at ? $recepcion->created_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
            </p>
        </div>

    </div>

    <div class="info-grid">
        <div><span>Proveedor / Persona:</span>
            <p>{{ $recepcion->persona->nombre ?? 'N/A' }}</p>
        </div>
    </div>

    <h2 style="margin-bottom: 0px;">Detalle de Contenedores (Recepción Original)</h2>
    <table style="font-size: 0.8rem; text-transform: uppercase; margin: 0;">
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
            @foreach ($contenedores as $item)
                @php $totalKilos += $item->kilos_netos; @endphp
                <tr>
                    <td>{{ $item->contenedor_id }}</td>
                    <td>{{ $item->producto ?? '-' }}</td>
                    <td>{{ $item->variedad ?? '-' }}</td>
                    <td>{{ $item->calibre ?? '-' }}</td>
                    <td class="text-right">
                        {{ $item->kilos_netos == (int) $item->kilos_netos ? number_format($item->kilos_netos, 0, ',', '.') : rtrim(number_format($item->kilos_netos, 2, ',', '.'), '0') }}
                        kg
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" class="text-right">Total Kilos Recepcionados:</td>
                <td class="text-right">
                    {{ $totalKilos == (int) $totalKilos ? number_format($totalKilos, 0, ',', '.') : rtrim(number_format($totalKilos, 2, ',', '.'), '0') }}
                    kg
                </td>
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
