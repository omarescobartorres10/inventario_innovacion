@extends('layouts.app')

@section('content')
    <header class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Control de entradas y salidas de insumos con una vista general de stock, alertas de bajo inventario y actividad reciente.</p>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('inventory.exports.summary.pdf') }}" class="button-link red"><i class="fa-solid fa-file-pdf"></i> Exportar PDF</a>
            <a href="{{ route('inventory.exports.summary.excel') }}" class="button-link green"><i class="fa-solid fa-file-excel"></i> Exportar Excel</a>
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
                        <h2><i class="fa-solid fa-clock-rotate-left"></i> Actividad reciente</h2>
                        <p>Últimos 5 movimientos registrados en el sistema.</p>
                    </div>
                    <a href="{{ route('inventory.movements.index') }}" class="pill blue"><i class="fa-solid fa-list"></i> Ver todos</a>
                </div>

                <div class="movement-list">
                    @forelse ($recentMovements as $movement)
                        <div class="movement-item">
                            <div class="movement-icon {{ $movement->type === 'entry' ? 'entry' : 'exit' }}">
                                <i class="fa-solid {{ $movement->type === 'entry' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                            </div>
                            <div class="movement-details">
                                <div class="movement-name">{{ $movement->item?->name }}</div>
                                <div class="movement-meta">
                                    <span class="movement-quantity">{{ $movement->quantity }} {{ $movement->item?->unit }}</span>
                                    <span class="movement-separator">·</span>
                                    <span class="movement-location">{{ $movement->fromLocation?->name ?? 'N/A' }} → {{ $movement->toLocation?->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="movement-type-badge">
                                <span class="pill {{ $movement->type === 'entry' ? 'green' : 'amber' }}">
                                    {{ $movement->type === 'entry' ? 'Entrada' : 'Salida' }}
                                </span>
                            </div>
                            <div class="movement-time">
                                {{ $movement->performed_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    @empty
                        <div class="movement-empty">
                            <i class="fa-solid fa-inbox"></i>
                            <span>Sin actividad reciente</span>
                        </div>
                    @endforelse
                </div>
            </article>
        </div>

        <div class="stack">
            <article class="panel">
                <div class="section-head">
                    <div>
                        <h2><i class="fa-solid fa-triangle-exclamation"></i> Bajo stock</h2>
                        <p>Insumos con existencias iguales o menores a 3 unidades.</p>
                    </div>
                    <a href="{{ route('inventory.items.index') }}" class="pill dark"><i class="fa-solid fa-boxes-stacked"></i> Ir a insumos</a>
                </div>

                <div class="list-card">
                    @forelse ($lowStockItems as $item)
                        <div class="list-card-item">
                            <div class="list-card-icon">
                                <i class="fa-solid fa-box-open"></i>
                            </div>
                            <div class="list-card-details">
                                <div class="list-card-name">{{ $item->name }}</div>
                                <div class="list-card-meta">
                                    <span>Bodega: <strong>{{ $item->bodega_stock }}</strong></span>
                                    <span>·</span>
                                    <span>Oficina: <strong>{{ $item->oficina_stock }}</strong></span>
                                </div>
                            </div>
                            <div class="list-card-status">
                                @if(($item->bodega_stock + $item->oficina_stock) === 0)
                                    <span class="pill red">Agotado</span>
                                @elseif(($item->bodega_stock + $item->oficina_stock) <= 1)
                                    <span class="pill amber">Crítico</span>
                                @else
                                    <span class="pill orange">Bajo</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="movement-empty">
                            <i class="fa-solid fa-check-circle"></i>
                            <span>Todo en orden — sin alertas</span>
                        </div>
                    @endforelse
                </div>
            </article>
        </div>
    </section>
@endsection
