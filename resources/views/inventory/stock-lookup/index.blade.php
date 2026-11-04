@extends('layouts.app')

@section('content')
    <header class="page-header">
        <div>
            <h1>Consulta de stock</h1>
            <p>Busca rapidamente un insumo y revisa su existencia actual en bodega, oficina y total general.</p>
        </div>
    </header>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Consulta rapida</h2>
                <p>Escribe nombre, marca o modelo para filtrar al instante.</p>
            </div>
            <span class="pill dark">{{ $items->count() }} insumos</span>
        </div>

        <div class="filters-panel">
            <div class="filters-grid" style="grid-template-columns: minmax(0, 1fr) auto;">
                <div>
                    <label for="stock_lookup_search">Buscar por insumo, marca o modelo</label>
                    <input id="stock_lookup_search" type="text" placeholder="Ejemplo: tinta, brother, MK120, bote..." data-stock-search>
                </div>
                <div class="filters-actions">
                    <button type="button" class="button-link dark" data-stock-clear><i class="fa-solid fa-eraser"></i> Limpiar</button>
                </div>
            </div>
        </div>

        <div class="stock-lookup-grid" data-stock-grid>
            @foreach ($items as $index => $item)
                @php
                    $statusClass = $item->total_stock <= 0 ? 'rose' : ($item->total_stock <= 3 ? 'amber' : 'green');
                @endphp
                <a
                    href="{{ route('inventory.delivery-act.form', ['item_id' => $item->id]) }}"
                    class="stock-card stock-card-link"
                    data-stock-card
                    data-search-text="{{ \Illuminate\Support\Str::lower(trim($item->name.' '.$item->brand.' '.$item->model)) }}"
                    data-initial-index="{{ $index }}"
                    @if ($index >= 2) style="display: none;" @endif
                >
                    <div class="stock-card-head">
                        <div>
                            <h3>{{ $item->name }}</h3>
                            <p>{{ $item->brand }} {{ $item->model }}</p>
                        </div>
                        <span class="pill {{ $statusClass }}">
                            {{ $item->total_stock <= 0 ? 'Agotado' : ($item->total_stock <= 3 ? 'Bajo stock' : 'Disponible') }}
                        </span>
                    </div>

                    <div class="stock-card-stats">
                        <div>
                            <small>Total</small>
                            <strong>{{ $item->total_stock }}</strong>
                        </div>
                        <div>
                            <small>Oficina</small>
                            <strong>{{ $item->oficina_stock }}</strong>
                        </div>
                        <div>
                            <small>Bodega</small>
                            <strong>{{ $item->bodega_stock }}</strong>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="message error" hidden data-stock-empty>
            No se encontraron insumos con ese criterio.
        </div>
    </section>

    <script>
        (() => {
            const input = document.querySelector('[data-stock-search]');
            const clearButton = document.querySelector('[data-stock-clear]');
            const cards = Array.from(document.querySelectorAll('[data-stock-card]'));
            const emptyState = document.querySelector('[data-stock-empty]');

            if (!input || cards.length === 0 || !clearButton || !emptyState) {
                return;
            }

            const normalize = (value) => value.toLowerCase().trim();

            const applyFilter = () => {
                const term = normalize(input.value);
                let visibleCount = 0;

                cards.forEach((card, index) => {
                    const text = card.dataset.searchText || normalize(card.textContent || '');
                    const visible = term === ''
                        ? index < 2
                        : text.includes(term);
                    card.style.display = visible ? '' : 'none';

                    if (visible) {
                        visibleCount += 1;
                    }
                });

                emptyState.hidden = visibleCount !== 0;
            };

            input.addEventListener('input', applyFilter);
            clearButton.addEventListener('click', () => {
                input.value = '';
                applyFilter();
                input.focus();
            });

            applyFilter();
        })();
    </script>
@endsection
