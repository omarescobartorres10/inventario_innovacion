@extends('layouts.app')

@section('content')
    @php($currentUser = auth()->user())
    <header class="page-header">
        <div>
            <h1>Catalogo</h1>
            <p>Administra las categorias que alimentan el modulo de equipos y sus asignaciones.</p>
        </div>
        <div class="toolbar">
            @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_CATALOGS))
                <button type="button" class="button-link dark" data-open-modal="category-create-modal"><i class="fa-solid fa-plus"></i> Nueva categoria</button>
            @endif
        </div>
    </header>

    <div class="toolbar" style="margin-bottom: 18px;">
        <a href="{{ route('catalogs.equipment-categories.index') }}" class="button-link dark"><i class="fa-solid fa-tags"></i> Categorias de equipos</a>
        <a href="{{ route('catalogs.municipal-areas.index') }}" class="button-link" style="background:#fff; border:1px solid rgba(20,33,43,.12); color:#14212b;"><i class="fa-solid fa-building-user"></i> Areas y responsables</a>
    </div>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Categorias de equipos</h2>
                <p>Estas categorias se usan en el formulario de equipos y en sus filtros.</p>
            </div>
            <span class="pill dark">{{ $categories->total() }} registros</span>
        </div>

        <form action="{{ route('catalogs.equipment-categories.index') }}" method="GET" class="filters-bar compact" style="margin-bottom: 18px;">
            <div>
                <label for="category_search">Buscar</label>
                <input id="category_search" name="search" type="text" value="{{ $filters['search'] ?? '' }}" placeholder="Nombre o slug">
            </div>
            <div class="filters-actions">
                <button type="submit"><i class="fa-solid fa-filter"></i> Filtrar</button>
            </div>
            <div class="filters-actions">
                <a href="{{ route('catalogs.equipment-categories.index') }}" class="button-link dark"><i class="fa-solid fa-eraser"></i> Limpiar</a>
            </div>
        </form>

        <div class="datatable-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th>Estado</th>
                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_CATALOGS))
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="strong">{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td><span class="pill {{ $category->is_active ? 'green' : 'rose' }}">{{ $category->is_active ? 'Activa' : 'Inactiva' }}</span></td>
                            @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_CATALOGS))
                                <td>
                                    <div class="toolbar">
                                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS))
                                            <a href="{{ route('catalogs.equipment-categories.index', ['edit' => $category->id]) }}" class="button-link dark"><i class="fa-solid fa-pen"></i> Editar</a>
                                        @endif
                                        @if ($currentUser->canDeleteIn(\App\Models\User::PERMISSION_CATALOGS))
                                            <form action="{{ route('catalogs.equipment-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Se eliminara esta categoria. Continuar?');">
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
                            <td colspan="{{ ($currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_CATALOGS)) ? 4 : 3 }}">No hay categorias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('inventory.partials.pagination', ['paginator' => $categories])
    </section>

    @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_CATALOGS))
        <div id="category-create-modal" class="modal {{ $errors->any() && !request('edit') ? 'is-visible' : '' }}">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Catalogo</span>
                        <h2>Nueva categoria</h2>
                    </div>
                    <button type="button" class="icon-button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form action="{{ route('catalogs.equipment-categories.store') }}" method="POST">
                    @csrf
                    @include('catalogs.equipment-categories.partials.form-fields', ['category' => null, 'submitLabel' => 'Guardar categoria'])
                </form>
            </div>
        </div>
    @endif

    @if ($editingCategory && $currentUser->canEditIn(\App\Models\User::PERMISSION_CATALOGS))
        <div id="category-edit-modal" class="modal is-visible">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Catalogo</span>
                        <h2>Editar categoria</h2>
                    </div>
                    <a href="{{ route('catalogs.equipment-categories.index') }}" class="icon-button"><i class="fa-solid fa-xmark"></i></a>
                </div>

                <form action="{{ route('catalogs.equipment-categories.update', $editingCategory) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('catalogs.equipment-categories.partials.form-fields', ['category' => $editingCategory, 'submitLabel' => 'Actualizar categoria'])
                </form>
            </div>
        </div>
    @endif
@endsection
