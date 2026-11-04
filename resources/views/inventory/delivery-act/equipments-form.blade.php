@extends('layouts.app')

@section('content')
    <header class="page-header">
        <div>
            <h1>Acta de entrega</h1>
            <p>Llena los datos institucionales y genera un PDF listo para imprimir con el formato oficial del acta de entrega de equipos.</p>
        </div>
    </header>

    <div class="toolbar" style="margin-bottom: 18px;">
        <a href="{{ route('inventory.delivery-act.form') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-boxes-stacked"></i> Acta de insumos</a>
        <a href="{{ route('inventory.delivery-act.equipments.form') }}" class="button-link dark"><i class="fa-solid fa-computer"></i> Acta de equipos</a>
        <a href="{{ route('inventory.delivery-act.history') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-clock-rotate-left"></i> Historial</a>
    </div>

    <section class="panel">
        <div class="section-head">
            <div>
                <h2>Captura del acta</h2>
                <p>Completa la informacion general y el detalle de equipos entregados.</p>
            </div>
        </div>

        <form action="{{ route('inventory.delivery-act.equipments.pdf') }}" method="POST" data-reset-after-submit>
            @csrf

            <div class="act-form-grid">
                <div>
                    <label for="delivery_date">Fecha</label>
                    <input id="delivery_date" name="delivery_date" type="date" value="{{ old('delivery_date', now()->timezone(config('app.timezone'))->format('Y-m-d')) }}" data-reset-value="{{ now()->timezone(config('app.timezone'))->format('Y-m-d') }}">
                </div>
                <div>
                    <label for="district">Distrito</label>
                    <input id="district" name="district" type="text" value="{{ old('district', 'Zacatecoluca') }}" placeholder="Zacatecoluca" data-reset-value="Zacatecoluca">
                </div>
            </div>

            <div class="act-form-grid">
                <div>
                    <label for="assigned_area_id">Area</label>
                    @php
                        $selectedArea = $areas->firstWhere('id', (int) old('assigned_area_id'));
                    @endphp
                    <div class="search-select" data-search-select>
                        <input
                            id="assigned_area_id"
                            type="text"
                            value="{{ $selectedArea?->name ?? '' }}"
                            placeholder="Selecciona area"
                            autocomplete="off"
                            data-search-input
                            data-area-select
                        >
                        <input type="hidden" name="assigned_area_id" value="{{ old('assigned_area_id') }}" data-search-value>
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
                    <input id="responsible_preview" type="text" value="" placeholder="Se cargara automaticamente" readonly data-area-responsible data-reset-value="">
                </div>
            </div>

            <div class="act-form-grid">
                <div>
                    <label for="receives">Recibe</label>
                    <input id="receives" name="receives" type="text" value="{{ old('receives') }}" placeholder="Nombre de quien recibe" data-reset-value="">
                </div>
                <div>
                    <label for="location_id">Ubicacion fisica</label>
                    @php
                        $selectedLocation = $locations->firstWhere('id', (int) old('location_id'));
                    @endphp
                    <div class="search-select" data-search-select>
                        <input
                            id="location_id"
                            type="text"
                            value="{{ $selectedLocation?->name ?? '' }}"
                            placeholder="Selecciona ubicacion"
                            autocomplete="off"
                            data-search-input
                        >
                        <input type="hidden" name="location_id" value="{{ old('location_id') }}" data-search-value>
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
                    <p class="subtle-note">Indica donde quedara el equipo despues de la entrega.</p>
                </div>
            </div>

            <div>
                <label>Detalle de equipos</label>
                <p class="subtle-note">Selecciona equipos reales del sistema. Al generar el PDF, quedaran marcados como asignados.</p>
            </div>

            <div class="rows-panel">
                @foreach ($defaultRows as $index => $row)
                    <div class="row-item">
                        <div style="grid-column: 1 / -1;">
                            <label for="equipment_{{ $index }}">Equipo</label>
                            @php
                                $selectedEquipment = $equipments->firstWhere('id', (int) ($row['equipment_id'] ?? 0));
                                $selectedEquipmentLabel = $selectedEquipment
                                    ? "{$selectedEquipment->name} | Serie: ".($selectedEquipment->serial_number ?: '-')." | Activo: ".($selectedEquipment->asset_code ?: '-')
                                    : '';
                            @endphp
                            <div class="search-select" data-search-select>
                                <input
                                    id="equipment_{{ $index }}"
                                    type="text"
                                    value="{{ $selectedEquipmentLabel }}"
                                    placeholder="Buscar equipo"
                                    autocomplete="off"
                                    data-search-input
                                >
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
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="toolbar">
                <button type="submit" class="green"><i class="fa-solid fa-file-pdf"></i> Generar PDF</button>
            </div>
        </form>
    </section>

    <script>
        (() => {
            const areaSelect = document.querySelector('[data-area-select]');
            const responsibleField = document.querySelector('[data-area-responsible]');

            if (!areaSelect || !responsibleField) {
                return;
            }

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

            if (roots.length === 0) {
                return;
            }

            roots.forEach((root) => {
                const input = root.querySelector('[data-search-input]');
                const valueField = root.querySelector('[data-search-value]');
                const menu = root.querySelector('[data-search-menu]');
                const empty = root.querySelector('[data-search-empty]');
                const options = Array.from(root.querySelectorAll('[data-search-option]'));

                if (!input || !valueField || !menu || !empty) {
                    return;
                }

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
                        input.dispatchEvent(new CustomEvent('searchselect:change', { bubbles: true }));
                    });
                });

                document.addEventListener('click', (event) => {
                    if (!root.contains(event.target)) {
                        closeMenu();
                    }
                });
            });
        })();

        (() => {
            const form = document.querySelector('[data-reset-after-submit]');

            if (!form) {
                return;
            }

            const resetFormState = () => {
                form.querySelectorAll('[data-reset-value]').forEach((field) => {
                    field.value = field.dataset.resetValue ?? '';
                });

                form.querySelectorAll('[data-search-select]').forEach((root) => {
                    const input = root.querySelector('[data-search-input]');
                    const valueField = root.querySelector('[data-search-value]');

                    if (input) {
                        input.value = input.dataset.resetValue ?? '';
                    }

                    if (valueField) {
                        valueField.value = valueField.dataset.resetValue ?? '';
                    }
                });

                form.querySelectorAll('[data-area-responsible]').forEach((field) => {
                    field.value = field.dataset.resetValue ?? '';
                });
            };

            form.addEventListener('submit', () => {
                window.setTimeout(resetFormState, 400);
            });
        })();
    </script>
@endsection
