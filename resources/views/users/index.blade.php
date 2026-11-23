@extends('layouts.app')

@section('content')
    @php
        $currentUser = auth()->user();
        $userFormFields = ['name', 'username', 'email', 'phone', 'position', 'role', 'permissions', 'is_active', 'password', 'password_confirmation'];
        $hasUserFormErrors = $errors->hasAny($userFormFields);
        $hasPermissionError = $errors->has('permission_error');
        $hasUserError = $errors->has('user');
    @endphp

    @if ($hasPermissionError)
        <div class="message error" style="margin-bottom: 18px;">
            {{ $errors->first('permission_error') }}
        </div>
    @endif

    @if ($hasUserError)
        <div class="message error" style="margin-bottom: 18px;">
            {{ $errors->first('user') }}
        </div>
    @endif

    <header class="page-header">
        <div>
            <h1>Usuarios</h1>
            <p>Administra credenciales y la informacion basica del personal con acceso al sistema.</p>
        </div>
        <div class="toolbar">
            @if ($currentUser?->canCreateIn(\App\Models\User::PERMISSION_USERS))
                <button type="button" class="button-link dark" data-open-modal="user-create-modal"><i class="fa-solid fa-user-plus"></i> Nuevo usuario</button>
            @endif
        </div>
    </header>

    <section class="panel" style="margin-top: 24px;">
        <div class="section-head">
            <div>
                <h2>Usuarios registrados</h2>
                <p>CRUD de usuarios con control de estado y acceso.</p>
            </div>
            <span class="pill dark">{{ $users->total() }} registros</span>
        </div>

        <div class="datatable-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Telefono</th>
                        <th>Cargo</th>
                        <th>Acceso</th>
                        <th>Estado</th>
                        @if ($currentUser?->canEditIn(\App\Models\User::PERMISSION_USERS) || $currentUser?->canDeleteIn(\App\Models\User::PERMISSION_USERS) || !$currentUser?->isAdmin())
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="strong">{{ $user->name }}</td>
                            <td>{{ $user->username ?: '-' }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?: '-' }}</td>
                            <td>{{ $user->position ?: '-' }}</td>
                            <td>
                                @if ($user->isAdmin())
                                    <span class="perm-badge usuarios" style="font-size:12px; padding: 4px 12px;">
                                        <i class="fa-solid fa-crown" style="font-size:10px;"></i> Administrador
                                    </span>
                                @else
                                    @php
                                        $groups = $user->permissionGroups();
                                        $moduleClassMap = [
                                            \App\Models\User::PERMISSION_DASHBOARD => 'dashboard',
                                            \App\Models\User::PERMISSION_ITEMS => 'insumos',
                                            \App\Models\User::PERMISSION_MOVEMENTS => 'movimientos',
                                            \App\Models\User::PERMISSION_EQUIPMENTS => 'equipos',
                                            \App\Models\User::PERMISSION_DELIVERY_ACTS => 'actas',
                                            \App\Models\User::PERMISSION_USERS => 'usuarios',
                                            \App\Models\User::PERMISSION_CATALOGS => 'catalogo',
                                            \App\Models\User::PERMISSION_EXPORTS => 'exportaciones',
                                        ];
                                    @endphp
                                    @if (empty($groups))
                                        <span class="perm-badge default">Sin permisos</span>
                                    @else
                                        @foreach ($groups as $module => $group)
                                            @php
                                                $moduleClass = $moduleClassMap[$module] ?? 'default';
                                                $isFull = $group['count'] === $group['total'];
                                            @endphp
                                            <span class="perm-group-badge perm-badge {{ $moduleClass }}" title="{{ $group['label'] }}: {{ implode(', ', $group['actions']) }}">
                                                {{ $group['label'] }}
                                                @if (!$isFull)
                                                    <span class="perm-group-count">{{ $group['count'] }}/{{ $group['total'] }}</span>
                                                @endif
                                            </span>
                                        @endforeach
                                    @endif
                                @endif
                            </td>
                            <td>
                                <span class="pill {{ $user->is_active ? 'green' : 'rose' }}">
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            @if ($currentUser?->canEditIn(\App\Models\User::PERMISSION_USERS) || $currentUser?->canDeleteIn(\App\Models\User::PERMISSION_USERS) || !$currentUser?->isAdmin())
                                <td>
                                    @if (($currentUser?->canEditIn(\App\Models\User::PERMISSION_USERS) || $currentUser?->canDeleteIn(\App\Models\User::PERMISSION_USERS)) && ($currentUser?->isAdmin() || !$user->isAdmin()))
                                        <div class="toolbar">
                                            @if ($currentUser?->canEditIn(\App\Models\User::PERMISSION_USERS) && ($currentUser?->isAdmin() || !$user->isAdmin()))
                                                <a href="{{ route('users.index', ['edit' => $user->id]) }}" class="button-link dark"><i class="fa-solid fa-pen"></i> Editar</a>
                                            @endif
                                            @if ($currentUser?->canDeleteIn(\App\Models\User::PERMISSION_USERS) && auth()->id() !== $user->id && ($currentUser?->isAdmin() || !$user->isAdmin()))
                                                <form action="{{ route('users.destroy', $user) }}" method="POST" data-confirm-delete="Se eliminará este usuario. Continuar?">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"><i class="fa-solid fa-trash"></i> Eliminar</button>
                                                </form>
                                            @endif
                                        </div>
                                    @else
                                        <span class="muted">Sin acceso</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ ($currentUser?->canEditIn(\App\Models\User::PERMISSION_USERS) || $currentUser?->canDeleteIn(\App\Models\User::PERMISSION_USERS) || !$currentUser?->isAdmin()) ? 8 : 7 }}">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('inventory.partials.pagination', ['paginator' => $users])
    </section>

    @if ($currentUser?->canCreateIn(\App\Models\User::PERMISSION_USERS))
        <div id="user-create-modal" class="modal {{ $hasUserFormErrors && !request('edit') ? 'is-visible' : '' }}">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Acceso al sistema</span>
                        <h2>Nuevo usuario</h2>
                    </div>
                    <button type="button" class="icon-button" data-close-modal><i class="fa-solid fa-xmark"></i></button>
                </div>

                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    @include('users.partials.form-fields', ['user' => null, 'submitLabel' => 'Guardar usuario'])
                </form>
            </div>
        </div>
    @endif

    @if ($editingUser && $currentUser?->canEditIn(\App\Models\User::PERMISSION_USERS) && !$hasPermissionError)
        <div id="user-edit-modal" class="modal is-visible">
            <div class="modal-card">
                <div class="modal-head">
                    <div>
                        <span class="hero-note">Acceso al sistema</span>
                        <h2>Editar usuario</h2>
                    </div>
                    <a href="{{ route('users.index') }}" class="icon-button"><i class="fa-solid fa-xmark"></i></a>
                </div>

                <form action="{{ route('users.update', $editingUser) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @include('users.partials.form-fields', ['user' => $editingUser, 'submitLabel' => 'Actualizar usuario'])
                </form>
            </div>
        </div>
    @endif
@endsection
