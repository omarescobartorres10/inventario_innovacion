@extends('layouts.app')

@section('content')
    @php
        $selectedFilterItem = $items->firstWhere('id', (int) ($filters['item_id'] ?? 0));
        $selectedFilterItemLabel = $selectedFilterItem
            ? trim("{$selectedFilterItem->name} - {$selectedFilterItem->brand} {$selectedFilterItem->model}")
            : '';
        $selectedFilterLocation = $locations->firstWhere('id', (int) ($filters['location_id'] ?? 0));
    @endphp
    @php
        $movementFormFields = ['item_id', 'type', 'quantity', 'from_location_id', 'to_location_id', 'notes', 'performed_at'];
        $hasMovementFormErrors = $errors->hasAny($movementFormFields);
    @endphp
    <header class="page-header">
        <div>
            <h1>Movimientos</h1>
            <p>Registra entradas y salidas sobre insumos existentes y consulta el historial paginado del inventario.</p>
        </div>
        <div class="toolbar">
            @if (auth()->user()?->canCreateIn(\App\Models\User::PERMISSION_MOVEMENTS))
                <button type="button" class="button-link dark" data-open-modal="movement-modal"><i
                        class="fa-solid fa-plus"></i> Nuevo movimiento</button>
            @endif
            <a href="{{ route('inventory.exports.summary.pdf') }}" class="button-link red"><i
                    class="fa-solid fa-file-pdf"></i> Resumen PDF</a>
            <a href="{{ route('inventory.exports.summary.excel') }}" class="button-link green"><i
                    class="fa-solid fa-file-excel"></i> Resumen Excel</a>
        </div>
    </header>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <span class="pill dark">{{ $movements->total() }} registros</span>
        </div>

        <div class="search-bar">
            <i class="fa-solid fa-search"></i>
            <input id="table_search" type="text" placeholder="Buscar en la tabla por fecha, insumo, tipo, origen, destino..." autocomplete="off">
            <span id="search_count" class="search-count"></span>
        </div>

        <div class="datatable-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Registrado por</th>
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
                            <td>
                                {{ $movement->performer ? $movement->performer->name : 'Sistema (Migración)' }}
                            </td>
                            <td class="strong">{{ $movement->item?->name }} {{ $movement->item?->brand }}
                                {{ $movement->item?->model }}</td>
                            <td><span
                                    class="pill {{ $movement->type === 'entry' ? 'green' : ($movement->type === 'transfer' ? 'blue' : 'amber') }}">{{ $movementTypes[$movement->type] }}</span>
                            </td>
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
        <div id="movement-modal" class="modal {{ $hasMovementFormErrors ? 'is-visible' : '' }}">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Operacion de inventario</span>
                        <h2>Nuevo movimiento</h2>
                    </div>
                    <button type="button" class="icon-button" data-close-modal><i
                            class="fa-solid fa-xmark"></i></button>
                </div>

                <form action="{{ route('inventory.movements.store') }}" method="POST">
                    @csrf
                    <div class="filter-inline-search">
                        <label for="item_search">Insumo</label>
                        @php
                            $selectedItem = $items->firstWhere('id', (int) old('item_id'));
                            $selectedItemLabel = $selectedItem
                                ? "{$selectedItem->name} - {$selectedItem->brand} {$selectedItem->model} (Bodega: {$selectedItem->bodega_stock}, Oficina: {$selectedItem->oficina_stock})"
                                : '';
                        @endphp
                        <input id="item_search" type="text" value="{{ $selectedItemLabel }}"
                            placeholder="Buscar por nombre, marca o modelo" autocomplete="off">
                        <input type="hidden" id="item_id" name="item_id" value="{{ old('item_id') }}">
                        <div class="inline-options" id="item_search_options">
                            @foreach ($items as $item)
                                <button type="button" class="inline-option" data-value="{{ $item->id }}"
                                    data-label="{{ $item->name }} - {{ $item->brand }} {{ $item->model }} (Bodega: {{ $item->bodega_stock }}, Oficina: {{ $item->oficina_stock }})">
                                    {{ $item->name }} - {{ $item->brand }} {{ $item->model }} (Bodega:
                                    {{ $item->bodega_stock }}, Oficina: {{ $item->oficina_stock }})
                                </button>
                            @endforeach
                        </div>
                        <div class="no-results" hidden id="item_search_no_results">No se encontraron insumos.</div>
                    </div>
                    <div class="field-grid">
                        <div>
                            <label for="type">Tipo</label>
                            <select id="type" name="type" data-movement-type>
                                @foreach ($movementTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="quantity">Cantidad</label>
                            <input id="quantity" name="quantity" type="number" min="1"
                                value="{{ old('quantity', 1) }}">
                        </div>
                    </div>
                    <div class="field-grid">
                        <div>
                            <label for="from_location_id">Origen</label>
                            <select id="from_location_id" name="from_location_id" data-from-location>
                                <option value="">Selecciona origen</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" @selected((string) old('from_location_id') === (string) $location->id)>
                                        {{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="to_location_id">Destino</label>
                            <select id="to_location_id" name="to_location_id" data-to-location>
                                <option value="">Selecciona destino</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" @selected((string) old('to_location_id') === (string) $location->id)>
                                        {{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="performed_at">Fecha y hora</label>
                        <input id="performed_at" name="performed_at" type="datetime-local"
                            value="{{ old('performed_at', now()->timezone(config('app.timezone'))->format('Y-m-d\\TH:i')) }}">
                    </div>
                    <div>
                        <label for="notes">Observaciones</label>
                        <textarea id="notes" name="notes" placeholder="Compra, asignacion, uso interno...">{{ old('notes') }}</textarea>
                    </div>
                    <button type="submit" class="green"><i class="fa-solid fa-floppy-disk"></i> Guardar
                        movimiento</button>
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

        // Búsqueda en tabla: filtrar filas conforme se escribe
        (() => {
            const input = document.getElementById('table_search');
            const countLabel = document.getElementById('search_count');
            if (!input) return;

            const tbody = document.querySelector('.datatable-wrap tbody');
            if (!tbody) return;

            const rows = Array.from(tbody.querySelectorAll('tr'));
            const noRow = tbody.querySelector('tr td[colspan]');

            let debounceTimer = null;

            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(filterRows, 100);
            });

            function filterRows() {
                const term = input.value.trim().toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    const visible = term === '' || text.includes(term);
                    row.style.display = visible ? '' : 'none';
                    if (visible) visibleCount++;
                });

                // Mostrar/ocultar fila de "no hay resultados"
                if (noRow) {
                    noRow.style.display = term === '' && visibleCount === 0 ? '' : (term === '' ? 'none' : (visibleCount === 0 ? '' : 'none'));
                }

                // Contador de resultados
                if (term !== '') {
                    countLabel.textContent = visibleCount + ' de ' + rows.length + ' registros';
                } else {
                    countLabel.textContent = '';
                }
            }
        })();

        // Búsqueda inline en modal de nuevo movimiento
        (() => {
            const input = document.getElementById('item_search');
            const optionsContainer = document.getElementById('item_search_options');
            const valueField = document.getElementById('item_id');
            const noResults = document.getElementById('item_search_no_results');

            if (!input || !optionsContainer) return;

            const options = Array.from(optionsContainer.querySelectorAll('.inline-option'));
            let debounceTimer = null;

            const filterOptions = () => {
                const term = input.value.trim().toLowerCase();
                let visibleCount = 0;

                options.forEach(option => {
                    const label = (option.dataset.label || '').toLowerCase();
                    const visible = term === '' || label.includes(term);
                    option.style.display = visible ? '' : 'none';
                    if (visible) visibleCount++;
                });

                if (noResults) {
                    noResults.hidden = visibleCount !== 0;
                }
                optionsContainer.style.display = visibleCount === 0 && term !== '' ? 'none' : '';
            };

            const debouncedFilter = () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(filterOptions, 150);
            };

            input.addEventListener('input', () => {
                if (valueField) valueField.value = '';
                debouncedFilter();
            });

            options.forEach(option => {
                option.addEventListener('click', () => {
                    input.value = option.dataset.label || '';
                    if (valueField) valueField.value = option.dataset.value || '';
                    optionsContainer.style.display = 'none';
                    if (noResults) noResults.style.display = 'none';
                });
            });

            document.addEventListener('click', (event) => {
                if (!input.contains(event.target) && !optionsContainer.contains(event.target)) {
                    optionsContainer.style.display = 'none';
                    if (noResults) noResults.style.display = 'none';
                }
            });

            input.addEventListener('focus', () => {
                optionsContainer.style.display = '';
                filterOptions();
            });
        })();
    </script>
@endsection
