@extends('layouts.app')

@section('content')
    @php($currentUser = auth()->user())
    <header class="page-header">
        <div>
            <h1>Historial de actas</h1>
            <p>Consulta las actas emitidas de insumos y equipos con su informacion principal y detalle registrado.</p>
        </div>
        <div class="toolbar">
            <a href="{{ route('inventory.delivery-act.form') }}" class="button-link dark"><i class="fa-solid fa-file-signature"></i> Acta de insumos</a>
            <a href="{{ route('inventory.delivery-act.equipments.form') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-computer"></i> Acta de equipos</a>
        </div>
    </header>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Actas emitidas</h2>
                <p>Filtra por tipo o por texto para ubicar rapidamente un acta generada.</p>
            </div>
            <span class="pill dark">{{ $records->total() }} registros</span>
        </div>

        <form action="{{ route('inventory.delivery-act.history') }}" method="GET" class="filters-panel">
            <div class="filters-grid" style="grid-template-columns: minmax(0, 1.5fr) minmax(220px, 0.8fr) auto;">
                <div>
                    <label for="history_search">Buscar</label>
                    <input id="history_search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="Distrito, area, responsable o recibe">
                </div>
                <div>
                    <label for="history_type">Tipo</label>
                    <select id="history_type" name="type">
                        <option value="">Todas</option>
                        @foreach ($recordTypes as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['type'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filters-actions">
                    <button type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
                    <a href="{{ route('inventory.delivery-act.history') }}" class="button-link dark"><i class="fa-solid fa-eraser"></i> Limpiar</a>
                </div>
            </div>
        </form>

        <div class="list">
            @forelse ($records as $record)
                <article class="list-card">
                    <div class="section-head" style="margin-bottom: 12px;">
                        <div>
                            <h2 style="font-size: 22px;">{{ $recordTypes[$record->type] ?? $record->type }}</h2>
                            <p>
                                {{ optional($record->delivery_date)->format('d/m/Y') }} |
                                {{ $record->district }} |
                                {{ $record->area_name }}
                            </p>
                        </div>
                        <div class="toolbar" style="gap: 10px;">
                            @if ($record->annulled_at)
                                <span class="pill dark">Anulada</span>
                            @endif
                            <span class="pill {{ $record->type === 'equipments' ? 'blue' : 'green' }}">
                                {{ $record->total_lines }} detalle{{ $record->total_lines === 1 ? '' : 's' }}
                            </span>
                        </div>
                    </div>

                    <div class="field-grid">
                        <div>
                            <small class="muted">Responsable</small>
                            <div class="strong">{{ $record->responsible_name ?: 'Sin responsable' }}</div>
                        </div>
                        <div>
                            <small class="muted">Recibe</small>
                            <div class="strong">{{ $record->receives }}</div>
                        </div>
                    </div>

                    <div class="field-grid" style="margin-top: 14px;">
                        <div>
                            <small class="muted">Usuario que emitio</small>
                            <div class="strong">{{ $record->creator?->name ?: 'Sin registro' }}</div>
                        </div>
                        <div>
                            <small class="muted">Fecha y hora de emision</small>
                            <div class="strong">{{ $record->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?: '-' }}</div>
                        </div>
                    </div>

                    <div class="field-grid" style="margin-top: 14px;">
                        <div>
                            <small class="muted">Origen</small>
                            <div class="strong">{{ $record->from_location_name ?: '-' }}</div>
                        </div>
                        <div>
                            <small class="muted">Destino / Ubicacion</small>
                            <div class="strong">{{ $record->to_location_name ?: '-' }}</div>
                        </div>
                    </div>

                    @if ($record->annulled_at)
                        <div class="field-grid" style="margin-top: 14px;">
                            <div>
                                <small class="muted">Anulada por</small>
                                <div class="strong">{{ $record->annulledBy?->name ?: 'Sin registro' }}</div>
                            </div>
                            <div>
                                <small class="muted">Fecha de anulacion</small>
                                <div class="strong">{{ $record->annulled_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?: '-' }}</div>
                            </div>
                        </div>
                    @endif

                    @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_DELIVERY_ACTS) && ! $record->annulled_at)
                        <div class="toolbar" style="margin-top: 16px;">
                            <a href="{{ route('inventory.delivery-act.edit', $record) }}" class="button-link dark">
                                <i class="fa-solid fa-pen-to-square"></i> Corregir acta
                            </a>
                            @if ($record->type === \App\Models\DeliveryRecord::TYPE_SUPPLIES)
                                <form action="{{ route('inventory.delivery-act.annul', $record) }}" method="POST" onsubmit="return confirm('Anular esta acta devolverá el stock al origen. ¿Continuar?');">
                                    @csrf
                                    <button type="submit" class="button-link" style="border:0; background:#fff2f2; color:#a11f1f;">
                                        <i class="fa-solid fa-ban"></i> Anular acta
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <div class="datatable-wrap" style="margin-top: 16px;">
                        <table>
                            <thead>
                                <tr>
                                    <th>Detalle</th>
                                    <th class="text-right">Cantidad</th>
                                    <th>Activo</th>
                                    <th>Serie</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($record->lines as $line)
                                    <tr>
                                        <td>{{ $line->description }}</td>
                                        <td class="text-right">{{ $line->quantity ?: '-' }}</td>
                                        <td>{{ $line->asset_code ?: '-' }}</td>
                                        <td>{{ $line->serial_number ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </article>
            @empty
                <div class="empty">Aun no hay actas emitidas registradas.</div>
            @endforelse
        </div>

        @include('inventory.partials.pagination', ['paginator' => $records])
    </section>
@endsection
