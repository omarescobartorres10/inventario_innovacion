@extends('layouts.app')

@section('content')
    @php
        $selectedFilterItem = $items->firstWhere('id', (int) ($filters['item_id'] ?? 0));
        $selectedFilterItemLabel = $selectedFilterItem
            ? trim("{$selectedFilterItem->name} - {$selectedFilterItem->brand} {$selectedFilterItem->model}")
            : '';
        $selectedFilterLocation = $locations->firstWhere('id', (int) ($filters['location_id'] ?? 0));
    @endphp
    <header class="page-header">
        <div>
            <h1>Movimientos</h1>
            <p>Registra entradas y salidas sobre insumos existentes y consulta el historial paginado del inventario.</p>
        </div>
        <div class="toolbar">
            @if (auth()->user()?->canCreateIn(\App\Models\User::PERMISSION_MOVEMENTS))
                <button type="button" class="button-link dark" data-open-modal="movement-modal"><i class="fa-solid fa-plus"></i> Nuevo movimiento</button>
            @endif
            <a href="{{ route('inventory.exports.summary.pdf') }}" class="button-link dark"><i class="fa-solid fa-file-pdf"></i> Resumen PDF</a>
            <a href="{{ route('inventory.exports.summary.excel') }}" class="button-link green"><i class="fa-solid fa-file-excel"></i> Resumen Excel</a>
        </div>
    </header>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <span class="pill dark">{{ $movements->total() }} registros</span>
        </div>

        <form action="{{ route('inventory.movements.index') }}" method="GET" class="filters-panel">
            <div class="filters-grid single-row">
                <div>
                    <label for="item_filter">Insumo</label>
                    <div class="search-select" data-search-select>
                        <input
                            id="item_filter"
                            type="text"
                            value="{{ $selectedFilterItemLabel }}"
                            placeholder="Todos los insumos"
                            autocomplete="off"
                            data-search-input
                        >
                        <input type="hidden" name="item_id" value="{{ $filters['item_id'] ?? '' }}" data-search-value>
                        <div class="search-select-menu" hidden data-search-menu>
                            <button type="button" class="search-select-option" data-search-option data-value="" data-label="">Todos los insumos</button>
                            @foreach ($items as $item)
                                <button
                                    type="button"
                                    class="search-select-option"
                                    data-search-option
                                    data-value="{{ $item->id }}"
                                    data-label="{{ $item->name }} - {{ $item->brand }} {{ $item->model }}"
                                >
                                    {{ $item->name }} - {{ $item->brand }} {{ $item->model }}
                                </button>
                            @endforeach
                            <div class="search-select-empty" hidden data-search-empty>No se encontraron insumos.</div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="type_filter">Tipo</label>
                    <div class="search-select" data-search-select>
                        <input
                            id="type_filter"
                            type="text"
                            value="{{ $movementTypes[$filters['type']] ?? '' }}"
                            placeholder="Todos los tipos"
                            autocomplete="off"
                            data-search-input
                        >
                        <input type="hidden" name="type" value="{{ $filters['type'] ?? '' }}" data-search-value>
                        <div class="search-select-menu" hidden data-search-menu>
                            <button type="button" class="search-select-option" data-search-option data-value="" data-label="">Todos los tipos</button>
                            @foreach ($movementTypes as $value => $label)
                                <button
                                    type="button"
                                    class="search-select-option"
                                    data-search-option
                                    data-value="{{ $value }}"
                                    data-label="{{ $label }}"
                                >
                                    {{ $label }}
                                </button>
                            @endforeach
                            <div class="search-select-empty" hidden data-search-empty>No se encontraron tipos.</div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="location_filter">Ubicacion</label>
                    <div class="search-select" data-search-select>
                        <input
                            id="location_filter"
                            type="text"
                            value="{{ $selectedFilterLocation?->name ?? '' }}"
                            placeholder="Todas las ubicaciones"
                            autocomplete="off"
                            data-search-input
                        >
                        <input type="hidden" name="location_id" value="{{ $filters['location_id'] ?? '' }}" data-search-value>
                        <div class="search-select-menu" hidden data-search-menu>
                            <button type="button" class="search-select-option" data-search-option data-value="" data-label="">Todas las ubicaciones</button>
                            @foreach ($locations as $location)
                                <button
                                    type="button"
                                    class="search-select-option"
                                    data-search-option
                                    data-value="{{ $location->id }}"
                                    data-label="{{ $location->name }}"
                                >
                                    {{ $location->name }}
                                </button>
                            @endforeach
                            <div class="search-select-empty" hidden data-search-empty>No se encontraron ubicaciones.</div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="date_from">Desde</label>
                    <input id="date_from" name="date_from" type="date" value="{{ $filters['date_from'] ?? '' }}">
                </div>
                <div>
                    <label for="date_to">Hasta</label>
                    <input id="date_to" name="date_to" type="date" value="{{ $filters['date_to'] ?? '' }}">
                </div>
                <div class="filters-actions">
                    <button type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
                    <a href="{{ route('inventory.movements.index') }}" class="button-link dark"><i class="fa-solid fa-eraser"></i> Limpiar</a>
                </div>
            </div>
        </form>

        <div class="datatable-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Insumo</th>
                        <th>Tipo</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Cantidad</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                        <tr>
                            <td>{{ $movement->performed_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                            <td class="strong">{{ $movement->item?->name }} {{ $movement->item?->brand }} {{ $movement->item?->model }}</td>
                            <td><span class="pill {{ $movement->type === 'entry' ? 'green' : ($movement->type === 'transfer' ? 'blue' : 'amber') }}">{{ $movementTypes[$movement->type] }}</span></td>
                            <td>{{ $movement->fromLocation?->name ?? '-' }}</td>
                            <td>{{ $movement->toLocation?->name ?? '-' }}</td>
                            <td>{{ $movement->quantity }} {{ $movement->item?->unit }}</td>
                            <td>{{ $movement->notes ?: 'Sin observaciones' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">No hay movimientos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('inventory.partials.pagination', ['paginator' => $movements])
    </section>

    @if (auth()->user()?->canCreateIn(\App\Models\User::PERMISSION_MOVEMENTS))
        <div id="movement-modal" class="modal {{ $errors->any() ? 'is-visible' : '' }}">
            <div class="modal-card">
            <div class="modal-head">
                <div>
                    <span class="hero-note">Operacion de inventario</span>
                    <h2>Nuevo movimiento</h2>
                </div>
                <button type="button" class="icon-button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('inventory.movements.store') }}" method="POST">
                @csrf
                <div>
                    <label for="item_search">Insumo</label>
                    @php
                        $selectedItem = $items->firstWhere('id', (int) old('item_id'));
                        $selectedItemLabel = $selectedItem
                            ? "{$selectedItem->name} - {$selectedItem->brand} {$selectedItem->model} (Bodega: {$selectedItem->bodega_stock}, Oficina: {$selectedItem->oficina_stock})"
                            : '';
                    @endphp
                    <div class="search-select" data-search-select>
                        <input
                            id="item_search"
                            type="text"
                            value="{{ $selectedItemLabel }}"
                            placeholder="Buscar por nombre, marca o modelo"
                            autocomplete="off"
                            data-search-input
                        >
                        <input type="hidden" id="item_id" name="item_id" value="{{ old('item_id') }}" data-search-value>
                        <div class="search-select-menu" hidden data-search-menu>
                            @foreach ($items as $item)
                                <button
                                    type="button"
                                    class="search-select-option"
                                    data-search-option
                                    data-value="{{ $item->id }}"
                                    data-label="{{ $item->name }} - {{ $item->brand }} {{ $item->model }} (Bodega: {{ $item->bodega_stock }}, Oficina: {{ $item->oficina_stock }})"
                                >
                                    {{ $item->name }} - {{ $item->brand }} {{ $item->model }} (Bodega: {{ $item->bodega_stock }}, Oficina: {{ $item->oficina_stock }})
                                </button>
                            @endforeach
                            <div class="search-select-empty" hidden data-search-empty>No se encontraron insumos.</div>
                        </div>
                    </div>
                </div>
                <div class="field-grid">
                    <div>
                        <label for="type">Tipo</label>
                        <select id="type" name="type" data-movement-type>
                            @foreach ($movementTypes as $value => $label)
                                <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="quantity">Cantidad</label>
                        <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}">
                    </div>
                </div>
                <div class="field-grid">
                    <div>
                        <label for="from_location_id">Origen</label>
                        <select id="from_location_id" name="from_location_id" data-from-location>
                            <option value="">Selecciona origen</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @selected((string) old('from_location_id') === (string) $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="to_location_id">Destino</label>
                        <select id="to_location_id" name="to_location_id" data-to-location>
                            <option value="">Selecciona destino</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @selected((string) old('to_location_id') === (string) $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="performed_at">Fecha y hora</label>
                    <input id="performed_at" name="performed_at" type="datetime-local" value="{{ old('performed_at', now()->timezone(config('app.timezone'))->format('Y-m-d\\TH:i')) }}">
                </div>
                <div>
                    <label for="notes">Observaciones</label>
                    <textarea id="notes" name="notes" placeholder="Compra, asignacion, uso interno...">{{ old('notes') }}</textarea>
                </div>
                <button type="submit" class="green"><i class="fa-solid fa-floppy-disk"></i> Guardar movimiento</button>
            </form>
            </div>
        </div>
    @endif

    <script>
        (() => {
            const typeField = document.querySelector('[data-movement-type]');
            const fromField = document.querySelector('[data-from-location]');
            const toField = document.querySelector('[data-to-location]');

            if (!typeField || !fromField || !toField) {
                return;
            }

            const syncMovementFields = () => {
                if (typeField.value === 'entry') {
                    fromField.value = '';
                    fromField.disabled = true;
                    toField.disabled = false;
                } else if (typeField.value === 'exit') {
                    toField.value = '';
                    toField.disabled = true;
                    fromField.disabled = false;
                } else {
                    fromField.disabled = false;
                    toField.disabled = false;
                }
            };

            typeField.addEventListener('change', syncMovementFields);
            syncMovementFields();
        })();

        (() => {
            const roots = Array.from(document.querySelectorAll('[data-search-select]'));

            if (roots.length === 0) {
                return;
            }

            roots.forEach((root) => {
                const input = root.querySelector('[data-search-input]');
                const valueField = root.querySelector('[data-search-value]');
                const menu = root.querySelector('[data-search-menu]');
                const empty = root.querySelector('[data-search-empty]');
                const options = Array.from(root.querySelectorAll('[data-search-option]'));

                const openMenu = () => {
                    menu.hidden = false;
                };

                const closeMenu = () => {
                    menu.hidden = true;
                };

                const filterOptions = () => {
                    const term = input.value.trim().toLowerCase();
                    let visibleCount = 0;

                    options.forEach((option) => {
                        const label = option.dataset.label.toLowerCase();
                        const visible = term === '' || label.includes(term);
                        option.hidden = !visible;
                        if (visible) {
                            visibleCount += 1;
                        }
                    });

                    empty.hidden = visibleCount !== 0;
                };

                input.addEventListener('focus', () => {
                    openMenu();
                    filterOptions();
                });

                input.addEventListener('input', () => {
                    valueField.value = '';
                    openMenu();
                    filterOptions();
                });

                options.forEach((option) => {
                    option.addEventListener('click', () => {
                        input.value = option.dataset.label;
                        valueField.value = option.dataset.value;
                        closeMenu();
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!root.contains(event.target)) {
                        closeMenu();
                    }
                });
            });
        })();
    </script>
@endsection
