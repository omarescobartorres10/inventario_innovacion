@extends('layouts.app')

@section('content')
    @php($currentUser = auth()->user())
    <header class="page-header">
        <div>
            <h1>Asignaciones</h1>
            <p>Gestiona a quien o a que area pertenece cada equipo, junto con ubicacion y fecha de asignacion.</p>
        </div>
    </header>

    <div class="toolbar" style="margin-bottom: 18px;">
        <a href="{{ route('equipments.index') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-computer"></i> Equipos</a>
        <a href="{{ route('equipments.assignments') }}" class="button-link dark"><i class="fa-solid fa-people-arrows"></i> Asignaciones</a>
        <a href="{{ route('equipments.movements') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-timeline"></i> Movimientos</a>
    </div>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Asignacion de equipos</h2>
                <p>Un equipo puede asignarse a una persona, a un area de la alcaldia, o a ambas.</p>
            </div>
            <span class="pill dark">{{ $equipments->total() }} registros</span>
        </div>

        <form action="{{ route('equipments.assignments') }}" method="GET" class="filters-grid single-row" style="margin-bottom: 18px;">
            <div>
                <label for="assignment_search">Buscar</label>
                <input id="assignment_search" name="search" type="text" value="{{ $filters['search'] }}" placeholder="Categoria, marca, modelo, serie, activo o area">
            </div>
            <div>
                <label for="assignment_status_filter">Estado</label>
                <select id="assignment_status_filter" name="status">
                    <option value="">Todos</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filters-actions">
                <button type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
                <a href="{{ route('equipments.assignments') }}" class="button-link dark"><i class="fa-solid fa-eraser"></i> Limpiar</a>
            </div>
        </form>

        <div class="datatable-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Serie / Activo</th>
                        <th>Ubicacion</th>
                        <th>Responsable</th>
                        <th>Area</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS))
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($equipments as $equipment)
                        <tr>
                            <td class="strong">{{ $equipment->name }}</td>
                            <td>
                                Serie: {{ $equipment->serial_number ?: '-' }}<br>
                                Activo: {{ $equipment->asset_code ?: '-' }}
                            </td>
                            <td>{{ $equipment->location?->name ?: '-' }}</td>
                            <td>{{ $equipment->assignedUser?->name ?: 'Sin asignar' }}</td>
                            <td>
                                {{ $equipment->assignedArea?->name ?: '-' }}
                                @if ($equipment->assignedArea?->responsible_name)
                                    <br><span class="muted">{{ $equipment->assignedArea->responsible_name }}</span>
                                @endif
                            </td>
                            <td>{{ $equipment->assigned_at?->format('d/m/Y') ?: '-' }}</td>
                            <td><span class="pill {{ $equipment->status === 'available' ? 'green' : ($equipment->status === 'assigned' ? 'blue' : ($equipment->status === 'maintenance' ? 'amber' : 'rose')) }}">{{ $statuses[$equipment->status] ?? $equipment->status }}</span></td>
                            @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS))
                                <td>
                                    <a href="{{ route('equipments.assignments', ['edit' => $equipment->id]) }}" class="button-link dark"><i class="fa-solid fa-pen"></i> Gestionar</a>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS) ? 8 : 7 }}">No hay equipos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('inventory.partials.pagination', ['paginator' => $equipments])
    </section>

    @if ($editingEquipment && $currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS))
        <div id="equipment-assignment-modal" class="modal is-visible">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Asignacion</span>
                        <h2>{{ $editingEquipment->name }}</h2>
                    </div>
                    <a href="{{ route('equipments.assignments') }}" class="icon-button"><i class="fa-solid fa-xmark"></i></a>
                </div>

                <form action="{{ route('equipments.assignments.update', $editingEquipment) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="field-grid">
                        <div>
                            <label for="assignment_status">Estado</label>
                            <select id="assignment_status" name="status" required data-equipment-status>
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $editingEquipment->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="assignment_location_id">Ubicacion</label>
                            <select id="assignment_location_id" name="location_id">
                                <option value="">Sin ubicacion</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" @selected((string) old('location_id', $editingEquipment->location_id) === (string) $location->id)>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field-grid">
                        <div>
                            <label for="assignment_user_id">Responsable (opcional)</label>
                            <select id="assignment_user_id" name="assigned_user_id" data-equipment-assignment-input>
                                <option value="">Sin asignar</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected((string) old('assigned_user_id', $editingEquipment->assigned_user_id) === (string) $user->id)>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="assignment_area_id">Area de la alcaldia</label>
                            <select id="assignment_area_id" name="assigned_area_id" data-equipment-assignment-input data-area-select>
                                <option value="">Sin area</option>
                                @foreach ($areas as $area)
                                    <option
                                        value="{{ $area->id }}"
                                        data-responsible="{{ $area->responsible_name }}"
                                        data-email="{{ $area->institutional_email }}"
                                        @selected((string) old('assigned_area_id', $editingEquipment->assigned_area_id) === (string) $area->id)
                                    >
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="field-grid">
                        <div>
                            <label for="assignment_date">Fecha de asignacion</label>
                            <input id="assignment_date" name="assigned_at" type="date" value="{{ old('assigned_at', $editingEquipment->assigned_at?->format('Y-m-d')) }}" data-equipment-assignment-input>
                        </div>
                        <div>
                            <label for="assignment_notes">Observaciones</label>
                            <textarea id="assignment_notes" name="notes" placeholder="Observaciones de entrega o ubicacion...">{{ old('notes', $editingEquipment->notes) }}</textarea>
                        </div>
                    </div>

                    <div class="field-grid" data-area-summary>
                        <div>
                            <label>Responsable del area</label>
                            <input type="text" value="{{ $editingEquipment->assignedArea?->responsible_name }}" readonly data-area-responsible>
                        </div>
                        <div>
                            <label>Correo institucional</label>
                            <input type="text" value="{{ $editingEquipment->assignedArea?->institutional_email }}" readonly data-area-email>
                        </div>
                    </div>

                    <p class="subtle-note" data-equipment-assignment-note>
                        Si el equipo se asigna solo a un area, deja vacio el campo de responsable y completa el area.
                    </p>

                    <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar asignacion</button>
                </form>
            </div>
        </div>
    @endif

    <script>
        (() => {
            const form = document.querySelector('form[action*="/asignaciones/"]');

            if (!form) {
                return;
            }

            const statusField = form.querySelector('[data-equipment-status]');
            const assignmentInputs = Array.from(form.querySelectorAll('[data-equipment-assignment-input]'));
            const assignmentNote = form.querySelector('[data-equipment-assignment-note]');
            const areaSelect = form.querySelector('[data-area-select]');
            const areaResponsible = form.querySelector('[data-area-responsible]');
            const areaEmail = form.querySelector('[data-area-email]');
            const areaSummary = form.querySelector('[data-area-summary]');

            const syncAssignmentFields = () => {
                const enabled = statusField.value === 'assigned';

                assignmentInputs.forEach((input) => {
                    input.disabled = !enabled;

                    if (!enabled) {
                        input.value = '';
                    }
                });

                if (assignmentNote) {
                    assignmentNote.hidden = !enabled;
                }
            };

            const syncAreaSummary = () => {
                if (!areaSelect || !areaResponsible || !areaEmail || !areaSummary) {
                    return;
                }

                const selected = areaSelect.options[areaSelect.selectedIndex];
                const responsible = selected?.dataset.responsible || '';
                const email = selected?.dataset.email || '';

                areaResponsible.value = responsible;
                areaEmail.value = email;
                areaSummary.hidden = !selected?.value;
            };

            statusField.addEventListener('change', syncAssignmentFields);
            areaSelect?.addEventListener('change', syncAreaSummary);
            syncAssignmentFields();
            syncAreaSummary();
        })();
    </script>
@endsection
