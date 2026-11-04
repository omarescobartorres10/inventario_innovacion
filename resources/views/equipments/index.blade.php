@extends('layouts.app')

@section('content')
    @php($currentUser = auth()->user())
    <header class="page-header">
        <div>
            <h1>Equipos</h1>
            <p>Administra equipos tecnologicos y sus caracteristicas sin mezclar el alta del activo con la asignacion.</p>
        </div>
        <div class="toolbar">
            @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_EQUIPMENTS))
                <button type="button" class="button-link dark" data-open-modal="equipment-create-modal"><i class="fa-solid fa-plus"></i> Nuevo equipo</button>
            @endif
        </div>
    </header>

    <div class="toolbar" style="margin-bottom: 18px;">
        <a href="{{ route('equipments.index') }}" class="button-link dark"><i class="fa-solid fa-computer"></i> Equipos</a>
        <a href="{{ route('equipments.assignments') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-people-arrows"></i> Asignaciones</a>
        <a href="{{ route('equipments.movements') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-timeline"></i> Movimientos</a>
    </div>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Inventario de equipos</h2>
                <p>CRUD de equipos con caracteristicas tecnicas y control de asignaciones.</p>
            </div>
            <span class="pill dark">{{ $equipments->total() }} registros</span>
        </div>

        <form action="{{ route('equipments.index') }}" method="GET" class="filters-grid single-row" style="margin-bottom: 18px;">
            <div>
                <label for="equipment_search">Buscar</label>
                <input id="equipment_search" name="search" type="text" value="{{ $filters['search'] }}" placeholder="Categoria, marca, modelo, serie o activo">
            </div>
            <div>
                <label for="equipment_category_filter">Categoria</label>
                <select id="equipment_category_filter" name="category">
                    <option value="">Todas</option>
                    @foreach ($categories as $value => $label)
                        <option value="{{ $value }}" @selected($filters['category'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="equipment_status_filter">Estado</label>
                <select id="equipment_status_filter" name="status">
                    <option value="">Todos</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filters-actions">
                <button type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
                <a href="{{ route('equipments.index') }}" class="button-link dark"><i class="fa-solid fa-eraser"></i> Limpiar</a>
            </div>
        </form>

        <div class="datatable-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Equipo</th>
                        <th>Categoria</th>
                        <th>Serie / Activo</th>
                        <th>Caracteristicas</th>
                        <th>Estado</th>
                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_EQUIPMENTS))
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($equipments as $equipment)
                        <tr>
                            <td class="strong">
                                {{ $categories[$equipment->category] ?? $equipment->category }} {{ $equipment->brand }} {{ $equipment->model }}<br>
                                <span class="muted">{{ $equipment->name }}</span>
                            </td>
                            <td>{{ $categories[$equipment->category] ?? $equipment->category }}</td>
                            <td>
                                Serie: {{ $equipment->serial_number ?: '-' }}<br>
                                Activo: {{ $equipment->asset_code ?: '-' }}
                            </td>
                            <td>
                                CPU: {{ $equipment->processor ?: '-' }}<br>
                                RAM: {{ $equipment->ram ?: '-' }}<br>
                                Disco: {{ $equipment->storage ?: '-' }}<br>
                                SO: {{ $equipment->operating_system ?: '-' }}
                            </td>
                            <td><span class="pill {{ $equipment->status === 'available' ? 'green' : ($equipment->status === 'assigned' ? 'blue' : ($equipment->status === 'maintenance' ? 'amber' : 'rose')) }}">{{ $statuses[$equipment->status] ?? $equipment->status }}</span></td>
                            @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_EQUIPMENTS))
                                <td>
                                    <div class="toolbar">
                                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS))
                                            <a href="{{ route('equipments.index', ['edit' => $equipment->id]) }}" class="button-link dark"><i class="fa-solid fa-pen"></i> Editar</a>
                                        @endif
                                        @if ($currentUser->canDeleteIn(\App\Models\User::PERMISSION_EQUIPMENTS))
                                            <form action="{{ route('equipments.destroy', $equipment) }}" method="POST" onsubmit="return confirm('Se eliminara este equipo. Continuar?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"><i class="fa-solid fa-trash"></i> Eliminar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ ($currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_EQUIPMENTS)) ? 6 : 5 }}">No hay equipos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('inventory.partials.pagination', ['paginator' => $equipments])
    </section>

    @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_EQUIPMENTS))
        <div id="equipment-create-modal" class="modal {{ $errors->any() && !request('edit') ? 'is-visible' : '' }}">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Activo tecnologico</span>
                        <h2>Nuevo equipo</h2>
                    </div>
                    <button type="button" class="icon-button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form action="{{ route('equipments.store') }}" method="POST">
                    @csrf
                    @include('equipments.partials.form-fields', ['equipment' => null, 'submitLabel' => 'Guardar equipo'])
                </form>
            </div>
        </div>
    @endif

    @if ($editingEquipment && $currentUser->canEditIn(\App\Models\User::PERMISSION_EQUIPMENTS))
        <div id="equipment-edit-modal" class="modal is-visible">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Activo tecnologico</span>
                        <h2>Editar equipo</h2>
                    </div>
                    <a href="{{ route('equipments.index') }}" class="icon-button"><i class="fa-solid fa-xmark"></i></a>
                </div>

                <form action="{{ route('equipments.update', $editingEquipment) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('equipments.partials.form-fields', ['equipment' => $editingEquipment, 'submitLabel' => 'Actualizar equipo'])
                </form>
            </div>
        </div>
    @endif

    <script>
        (() => {
            const forms = Array.from(document.querySelectorAll('form[action$="/equipos"], form[action*="/equipos/"]'));

            forms.forEach((form) => {
                const categoryField = form.querySelector('[data-equipment-category]');
                const hardwareSection = form.querySelector('[data-equipment-hardware-section]');
                const hardwareInputs = Array.from(form.querySelectorAll('[data-equipment-hardware-input]'));

                if (!categoryField || !hardwareSection) {
                    return;
                }

                const syncEquipmentFields = () => {
                    const showHardware = ['pc', 'laptop'].includes(categoryField.value);

                    hardwareSection.hidden = !showHardware;
                    hardwareInputs.forEach((input) => {
                        input.disabled = !showHardware;
                    });
                };

                categoryField.addEventListener('change', syncEquipmentFields);
                syncEquipmentFields();
            });
        })();
    </script>
@endsection
