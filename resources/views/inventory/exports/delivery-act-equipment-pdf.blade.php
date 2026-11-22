<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Acta de entrega de equipos</title>
    <style>
        @page { size: letter portrait; margin: 0; }
        body { margin: 0; font-family: "Century Gothic", "DejaVu Sans", sans-serif; color: #243244; font-size: 12px; }
        .sheet { position: relative; min-height: 1056px; padding: 92px 34px 120px; box-sizing: border-box; }
        .letterhead { position: fixed; top: 0; left: 0; width: 816px; height: 1056px; z-index: -1; }
        .top-logo { position: absolute; left: 34px; top: 50px; width: 88px; }
        .top-meta { text-align: right; font-size: 12px; line-height: 1.45; margin-top: 18px; }
        .title { margin: 28px 0 26px; text-align: center; font-size: 18px; font-weight: bold; letter-spacing: 0.01em; }
        .details { margin-top: 14px; }
        .line-row { margin: 8px 0; font-size: 13px; }
        .label { font-weight: bold; }
        .fill { display: inline-block; min-width: 420px; padding-bottom: 2px; border-bottom: 1px solid #243244; }
        .intro { margin: 12px 0 10px; font-size: 12px; line-height: 1.35; text-align: justify; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #5a6270; padding: 5px 8px; }
        th { text-align: center; font-size: 12px; font-weight: bold; }
        td { height: 20px; }
        .note { margin-top: 12px; line-height: 1.3; font-size: 11px; text-align: justify; padding-bottom: 52px; }
        .signatures { position: absolute; left: 34px; right: 34px; bottom: 146px; text-align: center; font-size: 12px; }
        .signature-line { display: inline-block; min-width: 250px; min-height: 14px; border-bottom: 1px solid #243244; }
        .footer-address { position: absolute; left: 120px; right: 160px; bottom: 24px; text-align: center; font-size: 9px; color: #243244; }
    </style>
</head>
<body>
    <div class="sheet">
        @if (is_file($letterheadPath))
            <img src="{{ $letterheadPath }}" alt="Membrete institucional" class="letterhead">
        @endif
        @if (is_file($logoPath))
            <img src="{{ $logoPath }}" alt="Logo La Paz Este" class="top-logo">
        @endif

        <div class="top-meta">
            Fecha: {{ $deliveryDate->format('d') }}/{{ $deliveryDate->format('m') }}/{{ $deliveryDate->format('Y') }}.<br>
            Distrito de {{ $district }}, La Paz Este
        </div>

        <div class="title">ACTA DE ENTREGA DE EQUIPOS</div>

        <div class="details">
            <div class="line-row"><span class="label">Responsable:</span> <span class="fill">{{ $responsible }}</span></div>
            <div class="line-row"><span class="label">Area:</span> <span class="fill">{{ $area }}</span></div>
        </div>

        <div class="intro">
            En cumplimiento de los controles institucionales de la alcaldia de La Paz Este, se deja constancia de la entrega de los siguientes equipos:
        </div>

        <table>
            <thead>
                <tr>
                    <th>EQUIPO</th>
                    <th>SERIE</th>
                    <th>ACTIVO</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $row)
                    <tr>
                        <td>{{ $row['equipment'] }}</td>
                        <td>{{ $row['serial_number'] }}</td>
                        <td>{{ $row['asset_code'] }}</td>
                    </tr>
                @endforeach
                @for ($i = $rows->count(); $i < 6; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endfor
            </tbody>
        </table>

        <div class="note">
            <strong>Nota:</strong> Los equipos entregados deberan ser utilizados exclusivamente para fines institucionales y de trabajo.
            Se solicita dar un uso adecuado y responsable a los mismos. En caso de desperfectos, traslado o cambio de responsable,
            debera informarse de inmediato al area correspondiente.
        </div>

        <div class="signatures">
            RECIBE: <span class="signature-line">{{ $receives }}</span><br><br>
            FIRMA Y SELLO: <span class="signature-line"></span>
        </div>

        <div class="footer-address">
            Alcaldia de La Paz Este, Av. Juan Manuel Rodriguez, Barrio El Calvario, Zacatecoluca.
        </div>
    </div>
</body>
</html>
