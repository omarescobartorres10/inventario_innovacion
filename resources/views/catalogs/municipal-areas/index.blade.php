@extends('layouts.app')

@section('content')
    @php($currentUser = auth()->user())
    <header class="page-header">
        <div>
            <h1>Catalogo</h1>
            <p>Administra las areas de la alcaldia, sus responsables y correos institucionales.</p>
        </div>
        <div class="toolbar">
            @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_CATALOGS))
                <button type="button" class="button-link dark" data-open-modal="area-create-modal"><i class="fa-solid fa-plus"></i> Nueva area</button>
            @endif
        </div>
    </header>

    <div class="toolbar" style="margin-bottom: 18px;">
        <a href="{{ route('catalogs.equipment-categories.index') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-tags"></i> Categorias de equipos</a>
        <a href="{{ route('catalogs.municipal-areas.index') }}" class="button-link dark"><i class="fa-solid fa-building-user"></i> Areas y responsables</a>
    </div>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Areas de la alcaldia</h2>
                <p>Catalogo base para asignaciones de equipos, con responsable y correo institucional.</p>
            </div>
            <span class="pill dark">{{ $areas->total() }} registros</span>
        </div>

        <form action="{{ route('catalogs.municipal-areas.index') }}" method="GET" class="filters-bar compact" style="margin-bottom: 18px;">
            <div>
                <label for="area_search">Buscar</label>
                <input id="area_search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="Area, responsable, correo o slug">
            </div>
            <div class="filters-actions">
                <button type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
            </div>
            <div class="filters-actions">
                <a href="{{ route('catalogs.municipal-areas.index') }}" class="button-link dark"><i class="fa-solid fa-eraser"></i> Limpiar</a>
            </div>
        </form>

        <div class="datatable-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Area</th>
                        <th>Responsable</th>
                        <th>Correo institucional</th>
                        <th>Estado</th>
                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_CATALOGS))
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($areas as $area)
                        <tr>
                            <td class="strong">{{ $area->name }}</td>
                            <td>{{ $area->responsible_name ?: '-' }}</td>
                            <td>{{ $area->institutional_email ?: '-' }}</td>
                            <td><span class="pill {{ $area->is_active ? 'green' : 'rose' }}">{{ $area->is_active ? 'Activa' : 'Inactiva' }}</span></td>
                            @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_CATALOGS))
                                <td>
                                    <div class="toolbar">
                                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS))
                                            <a href="{{ route('catalogs.municipal-areas.index', ['edit' => $area->id]) }}" class="button-link dark"><i class="fa-solid fa-pen"></i> Editar</a>
                                        @endif
                                        @if ($currentUser->canDeleteIn(\App\Models\User::PERMISSION_CATALOGS))
                                            <form action="{{ route('catalogs.municipal-areas.destroy', $area) }}" method="POST" onsubmit="return confirm('Se eliminara esta area. Continuar?');">
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
                            <td colspan="{{ ($currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_CATALOGS)) ? 5 : 4 }}">No hay areas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('inventory.partials.pagination', ['paginator' => $areas])
    </section>

    @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_CATALOGS))
        <div id="area-create-modal" class="modal {{ $errors->any() && !request('edit') ? 'is-visible' : '' }}">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Catalogo</span>
                        <h2>Nueva area</h2>
                    </div>
                    <button type="button" class="icon-button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form action="{{ route('catalogs.municipal-areas.store') }}" method="POST">
                    @csrf
                    @include('catalogs.municipal-areas.partials.form-fields', ['area' => null, 'submitLabel' => 'Guardar area'])
                </form>
            </div>
        </div>
    @endif

    @if ($editingArea && $currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS))
        <div id="area-edit-modal" class="modal is-visible">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Catalogo</span>
                        <h2>Editar area</h2>
                    </div>
                    <a href="{{ route('catalogs.municipal-areas.index') }}" class="icon-button"><i class="fa-solid fa-xmark"></i></a>
                </div>

                <form action="{{ route('catalogs.municipal-areas.update', $editingArea) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('catalogs.municipal-areas.partials.form-fields', ['area' => $editingArea, 'submitLabel' => 'Actualizar area'])
                </form>
            </div>
        </div>
    @endif
@endsection
