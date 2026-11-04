<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de movimientos de equipos</title>
    <style>
        @page {
            size: letter portrait;
            margin: 0;
        }

        body {
            margin: 0;
            font-family: "Century Gothic", "DejaVu Sans", sans-serif;
            color: #243244;
            font-size: 8px;
        }

        .sheet {
            position: relative;
            width: 816px;
            height: 1056px;
            overflow: hidden;
            padding: 84px 24px 18px;
            box-sizing: border-box;
        }

        .letterhead {
            position: absolute;
            top: 0;
            left: 0;
            width: 816px;
            height: 1056px;
            z-index: -1;
        }

        .top-logo {
            position: absolute;
            left: 30px;
            top: 44px;
            width: 74px;
        }

        h1 {
            margin: 14px 0 3px;
            text-align: center;
            font-size: 14px;
        }

        .subtitle {
            text-align: center;
            font-size: 9px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .meta {
            margin-bottom: 8px;
            color: #4b5563;
            line-height: 1.1;
            font-size: 7.5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 6px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 3px 4px;
            vertical-align: top;
            text-align: left;
            word-wrap: break-word;
        }

        th {
            background: #eef2f7;
            font-weight: bold;
            font-size: 7.5px;
        }

        td {
            font-size: 7px;
            line-height: 1.05;
        }

        .col-date {
            width: 11%;
        }

        .col-equipment {
            width: 21%;
        }

        .col-type {
            width: 10%;
        }

        .col-origin,
        .col-destination {
            width: 11%;
        }

        .col-assignment {
            width: 15%;
        }

        .col-tech {
            width: 12%;
        }

        .col-notes {
            width: 20%;
        }

        .summary {
            margin-top: 4px;
            font-size: 7px;
            color: #4b5563;
        }

        .address {
            text-align: center;
            font-size: 6.8px;
            color: #243244;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <div class="sheet">
        @if (is_file($letterheadPath))
            <img src="{{ $letterheadPath }}" alt="Membrete institucional" class="letterhead">
        @endif

        @if (is_file($logoPath))
            <img src="{{ $logoPath }}" alt="Logo institucional" class="top-logo">
        @endif

        <h1>REPORTE DE MOVIMIENTOS DE EQUIPOS</h1>
        <div class="subtitle">Secretaria de Innovacion y Digitalizacion</div>

        <div class="meta">
            Generado: {{ $generatedAt }}
        </div>

        <table>
            <thead>
                <tr>
                    <th class="col-date">Fecha</th>
                    <th class="col-equipment">Equipo</th>
                    <th class="col-type">Tipo</th>
                    <th class="col-origin">Origen</th>
                    <th class="col-destination">Destino</th>
                    <th class="col-assignment">Asignacion</th>
                    <th class="col-tech">Tecnico</th>
                    <th class="col-notes">Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $movement)
                    <tr>
                        <td>{{ $movement->performed_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                        <td>
                            {{ $movement->equipment?->name }}<br>
                            Serie: {{ $movement->equipment?->serial_number ?: '-' }}<br>
                            Activo: {{ $movement->equipment?->asset_code ?: '-' }}
                        </td>
                        <td>{{ $movementTypes[$movement->type] ?? $movement->type }}</td>
                        <td>{{ $movement->fromArea?->name ?: ($movement->fromLocation?->name ?: '-') }}</td>
                        <td>{{ $movement->toArea?->name ?: ($movement->toLocation?->name ?: '-') }}</td>
                        <td>
                            {{ $movement->assignedUser?->name ?: ($movement->assigned_user_name ?: '-') }}<br>
                            Area: {{ $movement->assigned_area_name ?: '-' }}
                        </td>
                        <td>{{ $movement->performer?->name ?: 'Sin usuario' }}</td>
                        <td>{{ $movement->notes ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">No hay movimientos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary">
            Registros incluidos: {{ $movements->count() }} de {{ $totalMovements }}.
            @if ($omittedMovements > 0)
                Se omitieron {{ $omittedMovements }} movimientos para conservar el reporte en una sola pagina.
            @endif
        </div>

        <div class="address">
            Alcaldia de La Paz Este, Av. Juan Manuel Rodriguez, Barrio El Calvario, Zacatecoluca.
        </div>
    </div>
</body>
</html>
