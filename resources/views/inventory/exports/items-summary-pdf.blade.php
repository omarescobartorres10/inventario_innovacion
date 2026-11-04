<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Resumen por insumo</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        h1, h2 {
            margin: 0 0 10px;
        }

        p {
            margin: 0 0 8px;
        }

        .block {
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="block">
        <h1>Resumen total por insumo</h1>
        <p>Generado: {{ $generatedAt }}</p>
        <p>Total de insumos: {{ $summary['items'] }}</p>
        <p>Stock global: {{ $summary['stock'] }}</p>
        <p>Stock en bodega: {{ $summary['bodega_stock'] }}</p>
        <p>Stock en oficina: {{ $summary['oficina_stock'] }}</p>
    </div>

    <div class="block">
        <h2>Detalle de insumos</h2>
        <table>
            <tr>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Unidad</th>
                <th>Bodega</th>
                <th>Oficina</th>
                <th>Total actual</th>
            </tr>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->brand }}</td>
                    <td>{{ $item->model }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ $item->bodega_stock }}</td>
                    <td>{{ $item->oficina_stock }}</td>
                    <td>{{ $item->total_stock }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
