@extends('layouts.app')

@section('content')
    @php
        $isSuppliesRecord = $record->type === \App\Models\DeliveryRecord::TYPE_SUPPLIES;
        $locationFieldName = $isSuppliesRecord ? 'from_location_id' : 'location_id';
        $locationFieldLabel = $isSuppliesRecord ? 'Ubicacion de salida' : 'Ubicacion fisica';
    @endphp
    <header class="page-header">
        <div>
            <h1>Corregir acta</h1>
            <p>
                Ajusta datos del documento{{ $isSuppliesRecord ? ' y el detalle de insumos' : ' y el detalle de equipos' }}
                para corregir errores de captura.
            </p>
        </div>
    </header>

    <div class="toolbar" style="margin-bottom: 18px;">
        <a href="{{ route('inventory.delivery-act.history') }}" class="button-link dark"><i class="fa-solid fa-clock-rotate-left"></i> Volver al historial</a>
        @if ($isSuppliesRecord)
            <a href="{{ route('inventory.delivery-act.form') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-boxes-stacked"></i> Nueva acta de insumos</a>
        @else
            <a href="{{ route('inventory.delivery-act.equipments.form') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-computer"></i> Nueva acta de equipos</a>
        @endif
    </div>

    <section class="panel">
        <div class="section-head">
            <div>
                <h2>{{ $recordTypes[$record->type] ?? $record->type }}</h2>
                <p>Acta #{{ $record->id }} emitida el {{ $record->created_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') ?: '-' }}.</p>
            </div>
        </div>

        @if (! $isSuppliesRecord)
            <div class="message ok" style="margin-bottom: 18px;">
                Esta correccion actualiza los datos del acta y reafirma la asignacion de los equipos seleccionados.
                Si retiras un equipo del documento, revisa luego su asignacion manualmente en el modulo de Equipos.
            </div>
        @endif

        <form action="{{ route('inventory.delivery-act.update', $record) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="act-form-grid">
                <div>
                    <label for="delivery_date">Fecha</label>
                    <input id="delivery_date" name="delivery_date" type="date" value="{{ old('delivery_date', optional($record->delivery_date)->format('Y-m-d')) }}">
                </div>
                <div>
                    <label for="district">Distrito</label>
                    <input id="district" name="district" type="text" value="{{ old('district', $record->district) }}" placeholder="Zacatecoluca">
                </div>
            </div>

            <div class="act-form-grid">
                <div>
                    <label for="assigned_area_id">Area</label>
                    @php
                        $selectedArea = $areas->firstWhere('id', (int) old('assigned_area_id', $record->assigned_area_id));
                    @endphp
                    <div class="search-select" data-search-select>
                        <input
                            id="assigned_area_id"
                            type="text"
                            value="{{ $selectedArea?->name ?? $record->area_name }}"
                            placeholder="Selecciona area"
                            autocomplete="off"
                            data-search-input
                            data-area-select
                        >
                        <input type="hidden" name="assigned_area_id" value="{{ old('assigned_area_id', $record->assigned_area_id) }}" data-search-value>
                        <div class="search-select-menu" hidden data-search-menu>
                            @foreach ($areas as $areaOption)
                                <button
                                    type="button"
                                    class="search-select-option"
                                    data-search-option
                                    data-value="{{ $areaOption->id }}"
                                    data-label="{{ $areaOption->name }}"
                                    data-responsible="{{ $areaOption->responsible_name }}"
                                >
                                    {{ $areaOption->name }}
                                </button>
                            @endforeach
                            <div class="search-select-empty" hidden data-search-empty>No se encontraron areas.</div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="responsible_preview">Responsable</label>
                    <input id="responsible_preview" type="text" value="{{ old('responsible_preview', $selectedArea?->responsible_name ?? $record->responsible_name) }}" readonly data-area-responsible>
                </div>
            </div>

            <div class="act-form-grid">
                <div>
                    <label for="receives">Recibe</label>
                    <input id="receives" name="receives" type="text" value="{{ old('receives', $record->receives) }}" placeholder="Nombre de quien recibe">
                </div>
                <div>
                    <label for="{{ $locationFieldName }}">{{ $locationFieldLabel }}</label>
                    @php
                        $selectedLocation = $locations->firstWhere('id', (int) old($locationFieldName, $isSuppliesRecord ? $record->from_location_id : $record->location_id));
                    @endphp
                    <div class="search-select" data-search-select>
                        <input
                            id="{{ $locationFieldName }}"
                            type="text"
                            value="{{ $selectedLocation?->name ?? ($isSuppliesRecord ? $record->from_location_name : $record->to_location_name) }}"
                            placeholder="Selecciona ubicacion"
                            autocomplete="off"
                            data-search-input
                        >
                        <input type="hidden" name="{{ $locationFieldName }}" value="{{ old($locationFieldName, $isSuppliesRecord ? $record->from_location_id : $record->location_id) }}" data-search-value>
                        <div class="search-select-menu" hidden data-search-menu>
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
            </div>

            <div>
                <label>{{ $isSuppliesRecord ? 'Detalle de insumos' : 'Detalle de equipos' }}</label>
            </div>

            <div class="rows-panel">
                @foreach ($defaultRows as $index => $row)
                    <div class="row-item">
                        @if ($isSuppliesRecord)
                            <div>
                                <label for="quantity_{{ $index }}">Cantidad</label>
                                <input id="quantity_{{ $index }}" name="rows[{{ $index }}][quantity]" type="text" value="{{ $row['quantity'] ?? '' }}" placeholder="1">
                            </div>
                        @endif
                        <div style="{{ $isSuppliesRecord ? '' : 'grid-column: 1 / -1;' }}">
                            <label for="detail_{{ $index }}">{{ $isSuppliesRecord ? 'Insumo' : 'Equipo' }}</label>
                            @if ($isSuppliesRecord)
                                @php
                                    $selectedItem = $items->firstWhere('id', (int) ($row['item_id'] ?? 0));
                                    $selectedItemLabel = $selectedItem
                                        ? "{$selectedItem->name} - {$selectedItem->brand} {$selectedItem->model} (Bodega: {$selectedItem->bodega_stock}, Oficina: {$selectedItem->oficina_stock})"
                                        : '';
                                @endphp
                                <div class="search-select" data-search-select>
                                    <input id="detail_{{ $index }}" type="text" value="{{ $selectedItemLabel }}" placeholder="Buscar insumo" autocomplete="off" data-search-input>
                                    <input type="hidden" name="rows[{{ $index }}][item_id]" value="{{ $row['item_id'] ?? '' }}" data-search-value>
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
                            @else
                                @php
                                    $selectedEquipment = $equipments->firstWhere('id', (int) ($row['equipment_id'] ?? 0));
                                    $selectedEquipmentLabel = $selectedEquipment
                                        ? "{$selectedEquipment->name} | Serie: ".($selectedEquipment->serial_number ?: '-')." | Activo: ".($selectedEquipment->asset_code ?: '-')
                                        : '';
                                @endphp
                                <div class="search-select" data-search-select>
                                    <input id="detail_{{ $index }}" type="text" value="{{ $selectedEquipmentLabel }}" placeholder="Buscar equipo" autocomplete="off" data-search-input>
                                    <input type="hidden" name="rows[{{ $index }}][equipment_id]" value="{{ $row['equipment_id'] ?? '' }}" data-search-value>
                                    <div class="search-select-menu" hidden data-search-menu>
                                        @foreach ($equipments as $equipment)
                                            <button
                                                type="button"
                                                class="search-select-option"
                                                data-search-option
                                                data-value="{{ $equipment->id }}"
                                                data-label="{{ $equipment->name }} | Serie: {{ $equipment->serial_number ?: '-' }} | Activo: {{ $equipment->asset_code ?: '-' }}"
                                            >
                                                {{ $equipment->name }} | Serie: {{ $equipment->serial_number ?: '-' }} | Activo: {{ $equipment->asset_code ?: '-' }}
                                            </button>
                                        @endforeach
                                        <div class="search-select-empty" hidden data-search-empty>No se encontraron equipos.</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="toolbar">
                <button type="submit" class="green"><i class="fa-solid fa-floppy-disk"></i> Guardar correccion</button>
            </div>
        </form>
    </section>

    <script>
        (() => {
            const areaSelect = document.querySelector('[data-area-select]');
            const responsibleField = document.querySelector('[data-area-responsible]');

            if (!areaSelect || !responsibleField) return;

            const syncAreaResponsible = () => {
                const root = areaSelect.closest('[data-search-select]');
                const valueField = root?.querySelector('[data-search-value]');
                const selected = root?.querySelector(`[data-search-option][data-value="${valueField?.value ?? ''}"]`);
                responsibleField.value = selected?.dataset.responsible || '';
            };

            areaSelect.addEventListener('input', syncAreaResponsible);
            areaSelect.addEventListener('searchselect:change', syncAreaResponsible);
            areaSelect.addEventListener('blur', syncAreaResponsible);
            syncAreaResponsible();
        })();

        (() => {
            const roots = Array.from(document.querySelectorAll('[data-search-select]'));
            if (roots.length === 0) return;

            roots.forEach((root) => {
                const input = root.querySelector('[data-search-input]');
                const valueField = root.querySelector('[data-search-value]');
                const menu = root.querySelector('[data-search-menu]');
                const empty = root.querySelector('[data-search-empty]');
                const options = Array.from(root.querySelectorAll('[data-search-option]'));

                if (!input || !valueField || !menu || !empty) return;

                const openMenu = () => { menu.hidden = false; };
                const closeMenu = () => { menu.hidden = true; };
                const filterOptions = () => {
                    const term = input.value.trim().toLowerCase();
                    let visibleCount = 0;

                    options.forEach((option) => {
                        const label = option.dataset.label.toLowerCase();
                        const visible = term === '' || label.includes(term);
                        option.hidden = !visible;
                        if (visible) visibleCount += 1;
                    });

                    empty.hidden = visibleCount !== 0;
                };

                input.addEventListener('focus', () => { openMenu(); filterOptions(); });
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
                        input.dispatchEvent(new CustomEvent('searchselect:change', { bubbles: true }));
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!root.contains(event.target)) closeMenu();
                });
            });
        })();
    </script>
@endsection
