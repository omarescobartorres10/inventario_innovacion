@extends('layouts.app')

@section('content')
    @php($currentUser = auth()->user())
    <header class="page-header">
        <div>
            <h1>Insumos</h1>
            <p>Registra nuevos insumos y consulta el listado paginado de productos ingresados al inventario.</p>
        </div>
        <div class="toolbar">
            @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_ITEMS))
                <button type="button" class="button-link dark" data-open-modal="item-modal"><i class="fa-solid fa-plus"></i> Nuevo insumo</button>
            @endif
            <a href="{{ route('inventory.exports.items-summary.pdf') }}" class="button-link dark"><i class="fa-solid fa-file-pdf"></i> Resumen PDF</a>
            <a href="{{ route('inventory.exports.items-summary.excel') }}" class="button-link green"><i class="fa-solid fa-file-excel"></i> Resumen Excel</a>
        </div>
    </header>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Insumos ingresados</h2>
                <p>Tabla paginada de 20 registros por pagina.</p>
            </div>
            <span class="pill dark">{{ $items->total() }} registros</span>
        </div>

        <form action="{{ route('inventory.items.index') }}" method="GET" class="filters-bar compact" style="margin-bottom: 18px;">
            <div>
                <label for="search">Buscar</label>
                <input id="search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="Nombre, marca o modelo" data-live-filter-input>
            </div>
            <div class="filters-actions">
                <button type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
            </div>
            <div class="filters-actions">
                <a href="{{ route('inventory.items.index') }}" class="button-link dark"><i class="fa-solid fa-eraser"></i> Limpiar</a>
            </div>
        </form>
        <div class="datatable-wrap">
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Insumo</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Unidad</th>
                        <th class="text-right">Bodega</th>
                        <th class="text-right">Oficina</th>
                        <th class="text-right">Total</th>
                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_ITEMS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_ITEMS))
                            <th class="text-right">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr data-filter-row>
                            <td class="strong">{{ $item->name }}</td>
                            <td>{{ $item->brand }}</td>
                            <td>{{ $item->model }}</td>
                            <td>{{ $item->unit }}</td>
                            <td class="text-right">
                                <span class="pill {{ $item->bodega_stock > 0 ? 'amber' : 'rose' }}">{{ $item->bodega_stock }}</span>
                            </td>
                            <td class="text-right">
                                <span class="pill {{ $item->oficina_stock > 0 ? 'blue' : 'rose' }}">{{ $item->oficina_stock }}</span>
                            </td>
                            <td class="text-right">
                                <span class="pill {{ $item->total_stock > 0 ? 'green' : 'rose' }}">{{ $item->total_stock }}</span>
                            </td>
                            @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_ITEMS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_ITEMS))
                                <td class="text-right">
                                    <div class="table-actions">
                                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_ITEMS))
                                            <button type="button" class="button-link dark" data-open-modal="edit-item-modal-{{ $item->id }}">
                                                <i class="fa-solid fa-pen-to-square"></i> Editar
                                            </button>
                                        @endif
                                        @if ($currentUser->canDeleteIn(\App\Models\User::PERMISSION_ITEMS))
                                            <form action="{{ route('inventory.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Se eliminara este insumo. Deseas continuar?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="button-link" style="background:#fff5f5; color:#b42318; border:1px solid rgba(180,35,24,.18);">
                                                    <i class="fa-solid fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr data-empty-row>
                            <td colspan="{{ ($currentUser->canEditIn(\App\Models\User::PERMISSION_ITEMS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_ITEMS)) ? 8 : 7 }}">No hay insumos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>

        @include('inventory.partials.pagination', ['paginator' => $items])
    </section>

    @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_ITEMS))
        <div id="item-modal" class="modal {{ $errors->any() ? 'is-visible' : '' }}">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Registro de catalogo</span>
                        <h2>Nuevo insumo</h2>
                    </div>
                    <button type="button" class="icon-button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form action="{{ route('inventory.items.store') }}" method="POST">
                    @csrf
                    <div>
                        <label for="name">Nombre</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="Tinta">
                    </div>
                    <div class="field-grid">
                        <div>
                            <label for="brand">Marca</label>
                            <input id="brand" name="brand" type="text" value="{{ old('brand') }}" placeholder="Brother">
                        </div>
                        <div>
                            <label for="model">Modelo</label>
                            <input id="model" name="model" type="text" value="{{ old('model') }}" placeholder="BTD60BK">
                        </div>
                    </div>
                    <div>
                        <label for="unit">Unidad</label>
                        <input id="unit" name="unit" type="text" value="{{ old('unit', 'unidad') }}" placeholder="unidad">
                    </div>
                    <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar insumo</button>
                </form>
            </div>
        </div>
    @endif

    @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_ITEMS))
        @foreach ($items as $item)
            <div id="edit-item-modal-{{ $item->id }}" class="modal">
                <div class="modal-card">
                    <div class="modal-head">
                        <div>
                            <span class="hero-note">Actualizacion de catalogo</span>
                            <h2>Editar insumo</h2>
                        </div>
                        <button type="button" class="icon-button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
                    </div>

                    <form action="{{ route('inventory.items.update', $item) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="edit_name_{{ $item->id }}">Nombre</label>
                            <input id="edit_name_{{ $item->id }}" name="name" type="text" value="{{ old('name', $item->name) }}" placeholder="Tinta">
                        </div>
                        <div class="field-grid">
                            <div>
                                <label for="edit_brand_{{ $item->id }}">Marca</label>
                                <input id="edit_brand_{{ $item->id }}" name="brand" type="text" value="{{ old('brand', $item->brand) }}" placeholder="Brother">
                            </div>
                            <div>
                                <label for="edit_model_{{ $item->id }}">Modelo</label>
                                <input id="edit_model_{{ $item->id }}" name="model" type="text" value="{{ old('model', $item->model) }}" placeholder="BTD60BK">
                            </div>
                        </div>
                        <div>
                            <label for="edit_unit_{{ $item->id }}">Unidad</label>
                            <input id="edit_unit_{{ $item->id }}" name="unit" type="text" value="{{ old('unit', $item->unit) }}" placeholder="unidad">
                        </div>
                        <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Guardar cambios</button>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

    <script>
        (() => {
            const input = document.querySelector('[data-live-filter-input]');
            const rows = Array.from(document.querySelectorAll('[data-filter-row]'));
            const emptyRow = document.querySelector('[data-empty-row]');

            if (!input || rows.length === 0) {
                return;
            }

            const normalize = (value) => value.toLowerCase().trim();

            const applyFilter = () => {
                const term = normalize(input.value);
                let visibleRows = 0;

                rows.forEach((row) => {
                    const text = normalize(row.textContent || '');
                    const matches = term === '' || text.includes(term);
                    row.hidden = !matches;

                    if (matches) {
                        visibleRows += 1;
                    }
                });

                if (emptyRow) {
                    emptyRow.hidden = visibleRows !== 0;
                }
            };

            input.addEventListener('input', applyFilter);
            applyFilter();
        })();
    </script>
@endsection
