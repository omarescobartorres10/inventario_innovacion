@extends('layouts.app')

@section('content')
    <header class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Control de entradas y salidas de insumos con una vista general de stock, alertas de bajo inventario y actividad reciente.</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('inventory.exports.summary.pdf') }}" class="pill dark"><i class="fa-solid fa-file-pdf"></i> Exportar PDF</a>
            <a href="{{ route('inventory.exports.summary.excel') }}" class="pill green"><i class="fa-solid fa-file-excel"></i> Exportar Excel</a>
        </div>
    </header>

    <section class="summary-row">
        <article class="summary-card">
            <small>Stock total</small>
            <strong>{{ $summary['stock'] }}</strong>
        </article>
        <article class="summary-card">
            <small>Total de insumos</small>
            <strong>{{ $summary['items'] }}</strong>
        </article>
        <article class="summary-card">
            <small>Entradas</small>
            <strong>{{ $summary['entries'] }}</strong>
        </article>
        <article class="summary-card">
            <small>Salidas</small>
            <strong>{{ $summary['exits'] }}</strong>
        </article>
    </section>

    <section class="summary-row" style="margin-top: -6px;">
        <article class="summary-card">
            <small>Stock bodega</small>
            <strong>{{ $summary['bodega_stock'] }}</strong>
        </article>
        <article class="summary-card">
            <small>Stock oficina</small>
            <strong>{{ $summary['oficina_stock'] }}</strong>
        </article>
        <article class="summary-card">
            <small>Transferencias</small>
            <strong>{{ $summary['transfers'] }}</strong>
        </article>
        <article class="summary-card">
            <small>Movimientos hoy</small>
            <strong>{{ $summary['movements_today'] }}</strong>
        </article>
    </section>

    <section class="summary-row" style="margin-top: -6px;">
        <article class="summary-card">
            <small>Insumos en alerta</small>
            <strong>{{ $summary['low_stock_items'] }}</strong>
        </article>
        <article class="summary-card">
            <small>Insumos agotados</small>
            <strong>{{ $summary['out_of_stock_items'] }}</strong>
        </article>
    </section>

    <section class="grid-2">
        <div class="stack">
            <article class="panel">
                <div class="section-head">
                    <div>
                        <h2>Actividad reciente</h2>
                        <p>Ultimos 5 movimientos registrados en el sistema.</p>
                    </div>
                    <a href="{{ route('inventory.movements.index') }}" class="pill blue"><i class="fa-solid fa-list"></i> Ver todos</a>
                </div>

                <div class="movement-list">
                    @forelse ($recentMovements as $movement)
                        <div class="movement-item">
                            <div>
                                <div class="strong">{{ $movement->item?->name }} {{ $movement->item?->brand }} {{ $movement->item?->model }}</div>
                                <p>{{ $movement->notes ?: 'Sin observaciones' }}</p>
                            </div>
                            <div>
                                <span class="pill {{ $movement->type === 'entry' ? 'green' : 'amber' }}">{{ $movementTypes[$movement->type] }}</span>
                                <p><span class="strong">{{ $movement->quantity }} {{ $movement->item?->unit }}</span></p>
                                <p>
                                    {{ $movement->fromLocation?->name ?? '-' }} -> {{ $movement->toLocation?->name ?? '-' }}
                                </p>
                                <p>{{ $movement->performed_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="empty">Todavia no se han registrado movimientos.</div>
                    @endforelse
                </div>
            </article>
        </div>

        <div class="stack">
            <article class="panel">
                <div class="section-head">
                    <div>
                        <h2>Bajo stock</h2>
                        <p>Insumos con existencias iguales o menores a 3 unidades.</p>
                    </div>
                    <a href="{{ route('inventory.items.index') }}" class="pill dark"><i class="fa-solid fa-boxes-stacked"></i> Ir a insumos</a>
                </div>

                <div class="list">
                    @forelse ($lowStockItems as $item)
                        <div class="list-card">
                            <div class="strong">{{ $item->name }} {{ $item->brand }} {{ $item->model }}</div>
                            <p class="muted">Unidad: {{ $item->unit }}</p>
                            <p style="margin-top: 10px;"><span class="pill {{ $item->bodega_stock > 0 ? 'amber' : 'rose' }}">Bodega {{ $item->bodega_stock }}</span></p>
                            <p style="margin-top: 10px;"><span class="pill {{ $item->oficina_stock > 0 ? 'blue' : 'rose' }}">Oficina {{ $item->oficina_stock }}</span></p>
                        </div>
                    @empty
                        <div class="empty">No hay insumos en nivel de alerta.</div>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
@endsection
