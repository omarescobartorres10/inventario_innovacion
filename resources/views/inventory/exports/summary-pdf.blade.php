<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Resumen de inventario</title>
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

        .meta, .block {
            margin-bottom: 18px;
        }

        .stats {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .stats td, .stats th, .table td, .table th {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        .stats th, .table th {
            background: #f3f4f6;
            text-align: left;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="meta">
        <h1>Resumen de Inventario Innovacion</h1>
        <p>Generado: {{ $generatedAt }}</p>
    </div>

    <div class="block">
        <h2>Indicadores generales</h2>
        <table class="stats">
            <tr><th>Indicador</th><th>Valor</th></tr>
            <tr><td>Stock total</td><td>{{ $summary['stock'] }}</td></tr>
            <tr><td>Total de insumos</td><td>{{ $summary['items'] }}</td></tr>
            <tr><td>Entradas</td><td>{{ $summary['entries'] }}</td></tr>
            <tr><td>Salidas</td><td>{{ $summary['exits'] }}</td></tr>
            <tr><td>Transferencias</td><td>{{ $summary['transfers'] }}</td></tr>
            <tr><td>Stock en bodega</td><td>{{ $summary['bodega_stock'] }}</td></tr>
            <tr><td>Stock en oficina</td><td>{{ $summary['oficina_stock'] }}</td></tr>
        </table>
    </div>

    <div class="block">
        <h2>Insumos</h2>
        <table class="table">
            <tr>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Bodega</th>
                <th>Oficina</th>
                <th>Total</th>
            </tr>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->brand }}</td>
                    <td>{{ $item->model }}</td>
                    <td>{{ $item->bodega_stock }}</td>
                    <td>{{ $item->oficina_stock }}</td>
                    <td>{{ $item->total_stock }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="block">
        <h2>Ultimos movimientos</h2>
        <table class="table">
            <tr>
                <th>Fecha</th>
                <th>Insumo</th>
                <th>Tipo</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Cantidad</th>
                <th>Observaciones</th>
                <th>Registrado por</th>
            </tr>
            @foreach ($movements as $movement)
                <tr>
                    <td>{{ $movement->performed_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                    <td>{{ $movement->item?->name }} {{ $movement->item?->brand }} {{ $movement->item?->model }}</td>
                    <td>{{ $movementTypes[$movement->type] }}</td>
                    <td>{{ $movement->fromLocation?->name ?? '-' }}</td>
                    <td>{{ $movement->toLocation?->name ?? '-' }}</td>
                    <td>{{ $movement->quantity }}</td>
                    <td>{{ $movement->notes ?: 'Sin observaciones' }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
