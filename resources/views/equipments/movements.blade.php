@extends('layouts.app')

@section('content')
    @php($currentUser = auth()->user())
    <header class="page-header">
        <div>
            <h1>Movimientos de equipos</h1>
            <p>Registra retiros, traslados y bajas de equipos, dejando trazabilidad del tecnico que ejecuta la operacion.</p>
        </div>
        <div class="toolbar">
            @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_EQUIPMENTS))
                <button type="button" class="button-link dark" data-open-modal="equipment-movement-modal"><i class="fa-solid fa-right-left"></i> Nuevo movimiento</button>
            @endif
            <a href="{{ route('equipments.movements.pdf', request()->query()) }}" class="button-link green"><i class="fa-solid fa-file-pdf"></i> Reporte PDF</a>
        </div>
    </header>

    <div class="toolbar" style="margin-bottom: 18px;">
        <a href="{{ route('equipments.index') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-computer"></i> Equipos</a>
        <a href="{{ route('equipments.assignments') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-people-arrows"></i> Asignaciones</a>
        <a href="{{ route('equipments.movements') }}" class="button-link dark"><i class="fa-solid fa-timeline"></i> Movimientos</a>
    </div>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Historial de movimientos</h2>
                <p>Consulta que tecnico retiro, traslado o dio de baja un equipo, y como estaba asignado en ese momento.</p>
            </div>
            <span class="pill dark">{{ $movements->total() }} registros</span>
        </div>

        <form action="{{ route('equipments.movements') }}" method="GET" class="filters-grid single-row" style="margin-bottom: 18px;">
            <div>
                <label for="equipment_movement_search">Buscar</label>
                <input id="equipment_movement_search" name="search" type="text" value="{{ $filters['search'] }}" placeholder="Equipo, serie, activo, tecnico, area o nota">
            </div>
            <div>
                <label for="equipment_movement_type">Tipo</label>
                <select id="equipment_movement_type" name="type">
                    <option value="">Todos</option>
                    @foreach ($movementTypes as $value => $label)
                        <option value="{{ $value }}" @selected($filters['type'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filters-actions">
                <button type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
                <a href="{{ route('equipments.movements') }}" class="button-link dark"><i class="fa-solid fa-eraser"></i> Limpiar</a>
            </div>
        </form>

        <div class="datatable-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Equipo</th>
                        <th>Tipo</th>
                        <th>Origen</th>
                        <th>Destino</th>
                        <th>Asignacion</th>
                        <th>Tecnico</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($movements as $movement)
                        <tr>
                            <td>{{ $movement->performed_at?->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</td>
                            <td class="strong">
                                {{ $movement->equipment?->name }}<br>
                                <span class="muted">Serie: {{ $movement->equipment?->serial_number ?: '-' }} | Activo: {{ $movement->equipment?->asset_code ?: '-' }}</span>
                            </td>
                            <td>
                                <span class="pill {{ $movement->type === 'transfer' ? 'blue' : ($movement->type === 'withdrawal' ? 'amber' : 'rose') }}">
                                    {{ $movementTypes[$movement->type] ?? $movement->type }}
                                </span>
                            </td>
                            <td>
                                {{ $movement->fromArea?->name ?: ($movement->fromLocation?->name ?: '-') }}
                            </td>
                            <td>
                                {{ $movement->toArea?->name ?: ($movement->toLocation?->name ?: '-') }}
                            </td>
                            <td>
                                {{ $movement->assignedUser?->name ?: ($movement->assigned_user_name ?: '-') }}<br>
                                <span class="muted">Area: {{ $movement->assigned_area_name ?: '-' }}</span>
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
        </div>

        @include('inventory.partials.pagination', ['paginator' => $movements])
    </section>

    @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_EQUIPMENTS))
        <div id="equipment-movement-modal" class="modal {{ $errors->any() ? 'is-visible' : '' }}">
            <div class="modal-card">
            <div class="modal-head">
                <div>
                    <span class="hero-note">Control operativo</span>
                    <h2>Nuevo movimiento de equipo</h2>
                </div>
                <button type="button" class="icon-button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
            </div>

            <form action="{{ route('equipments.movements.store') }}" method="POST">
                @csrf

                <div>
                    <label for="movement_equipment_id">Equipo</label>
                    <select id="movement_equipment_id" name="equipment_id" required>
                        <option value="">Selecciona equipo</option>
                        @foreach ($equipments as $equipment)
                            <option value="{{ $equipment->id }}" @selected((string) old('equipment_id') === (string) $equipment->id)>
                                {{ $equipment->name }} | Serie: {{ $equipment->serial_number ?: '-' }} | Responsable: {{ $equipment->assignedUser?->name ?: '-' }} | Area: {{ $equipment->assignedArea?->name ?: '-' }} | Ubicacion: {{ $equipment->location?->name ?: '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field-grid">
                    <div>
                        <label for="movement_type">Tipo</label>
                        <select id="movement_type" name="type" required data-equipment-movement-type>
                            @foreach ($movementTypes as $value => $label)
                                <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="movement_performed_at">Fecha y hora</label>
                        <input id="movement_performed_at" name="performed_at" type="datetime-local" value="{{ old('performed_at', now()->timezone(config('app.timezone'))->format('Y-m-d\\TH:i')) }}" required>
                    </div>
                </div>

                @if (auth()->user()->isAdmin())
                    <div>
                        <label for="movement_performed_by">Tecnico que realiza el movimiento</label>
                        <select id="movement_performed_by" name="performed_by">
                            <option value="">Selecciona tecnico</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" @selected((string) old('performed_by', auth()->id()) === (string) $user->id)>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div data-equipment-retirement-fields hidden>
                    <label for="movement_from_area_id">Area desde donde se retira</label>
                    <select id="movement_from_area_id" name="from_area_id">
                        <option value="">Selecciona area</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}" @selected((string) old('from_area_id') === (string) $area->id)>{{ $area->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field-grid" data-equipment-transfer-fields>
                    <div>
                        <label for="movement_to_location_id">Ubicacion destino</label>
                        <select id="movement_to_location_id" name="to_location_id">
                            <option value="">Sin cambio de ubicacion</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" @selected((string) old('to_location_id') === (string) $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="movement_to_area_id">Area destino</label>
                        <select id="movement_to_area_id" name="to_area_id">
                            <option value="">Sin cambio de area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" @selected((string) old('to_area_id') === (string) $area->id)>{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="movement_notes">Observaciones</label>
                    <textarea id="movement_notes" name="notes" placeholder="Motivo del retiro, traslado o baja...">{{ old('notes') }}</textarea>
                </div>

                <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar movimiento</button>
            </form>
            </div>
        </div>
    @endif

    <script>
        (() => {
            const typeField = document.querySelector('[data-equipment-movement-type]');
            const transferFields = document.querySelector('[data-equipment-transfer-fields]');
            const retirementFields = document.querySelector('[data-equipment-retirement-fields]');
            const destinationFields = Array.from(transferFields?.querySelectorAll('select') ?? []);
            const retirementSelect = retirementFields?.querySelector('select') ?? null;

            if (!typeField || !transferFields || !retirementFields) {
                return;
            }

            const syncTransferFields = () => {
                const isTransfer = typeField.value === 'transfer';
                const isRetirement = typeField.value === 'retirement';
                transferFields.hidden = !isTransfer;
                retirementFields.hidden = !isRetirement;

                destinationFields.forEach((field) => {
                    field.disabled = !isTransfer;

                    if (!isTransfer) {
                        field.value = '';
                    }
                });

                if (retirementSelect) {
                    retirementSelect.disabled = !isRetirement;

                    if (!isRetirement) {
                        retirementSelect.value = '';
                    }
                }
            };

            typeField.addEventListener('change', syncTransferFields);
            syncTransferFields();
        })();
    </script>
@endsection
