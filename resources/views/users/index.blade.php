@extends('layouts.app')

@section('content')
    @php
        $currentUser = auth()->user();
        $userFormFields = ['name', 'username', 'email', 'phone', 'position', 'role', 'permissions', 'is_active', 'password', 'password_confirmation'];
        $hasUserFormErrors = $errors->hasAny($userFormFields);
    @endphp

    <header class="page-header">
        <div>
            <h1>Usuarios</h1>
            <p>Administra credenciales y la informacion basica del personal con acceso al sistema.</p>
        </div>
        <div class="toolbar">
            @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_USERS))
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
                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_USERS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_USERS))
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
                                    Administrador
                                @else
                                    {{ implode(', ', $user->permissionLabels()) ?: 'Sin permisos' }}
                                @endif
                            </td>
                            <td>
                                <span class="pill {{ $user->is_active ? 'green' : 'rose' }}">
                                    {{ $user->is_active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_USERS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_USERS))
                                <td>
                                    <div class="toolbar">
                                        @if ($currentUser->canEditIn(\App\Models\User::PERMISSION_USERS))
                                            <a href="{{ route('users.index', ['edit' => $user->id]) }}" class="button-link dark"><i class="fa-solid fa-pen"></i> Editar</a>
                                        @endif
                                        @if ($currentUser->canDeleteIn(\App\Models\User::PERMISSION_USERS) && auth()->id() !== $user->id)
                                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Se eliminara este usuario. Continuar?');">
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
                            <td colspan="{{ ($currentUser->canEditIn(\App\Models\User::PERMISSION_USERS) || $currentUser->canDeleteIn(\App\Models\User::PERMISSION_USERS)) ? 8 : 7 }}">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @include('inventory.partials.pagination', ['paginator' => $users])
    </section>

    @if ($currentUser->canCreateIn(\App\Models\User::PERMISSION_USERS))
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

    @if ($editingUser && $currentUser->canEditIn(\App\Models\User::PERMISSION_USERS))
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
