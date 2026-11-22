@php
    $formKey = $user?->id ? 'user-'.$user->id : 'user-new';
@endphp

@if ($errors->any())
    <div class="message error" style="margin-bottom: 20px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px; animation: fadeIn 0.3s ease;">
        <i class="fa-solid fa-triangle-exclamation" style="margin-top: 2px;"></i>
        <div>
            <strong style="display: block; margin-bottom: 4px;">Por favor, corrige los siguientes errores:</strong>
            <ul style="margin: 0; padding-left: 16px; font-size: 13px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<div>
    <label for="user_name_{{ $formKey }}">Nombre</label>
    <input id="user_name_{{ $formKey }}" name="name" type="text" value="{{ old('name', $user?->name) }}" required>
</div>

<div class="field-grid">
    <div>
        <label for="user_username_{{ $formKey }}">Usuario</label>
        <input id="user_username_{{ $formKey }}" name="username" type="text" value="{{ old('username', $user?->username) }}" required>
    </div>
    <div>
        <label for="user_email_{{ $formKey }}">Correo</label>
        <input id="user_email_{{ $formKey }}" name="email" type="email" value="{{ old('email', $user?->email) }}" required>
    </div>
</div>

<div class="field-grid">
    <div>
        <label for="user_phone_{{ $formKey }}">Telefono</label>
        <input id="user_phone_{{ $formKey }}" name="phone" type="text" value="{{ old('phone', $user?->phone) }}">
    </div>
    <div></div>
</div>

<div class="field-grid">
    <div>
        <label for="user_position_{{ $formKey }}">Cargo</label>
        <input id="user_position_{{ $formKey }}" name="position" type="text" value="{{ old('position', $user?->position) }}">
    </div>
    <div>
        <label for="user_role_{{ $formKey }}">Tipo de acceso</label>
        <select id="user_role_{{ $formKey }}" name="role" data-user-role>
            <option value="{{ \App\Models\User::ROLE_ADMIN }}" @selected(old('role', $user?->role ?? \App\Models\User::ROLE_ADMIN) === \App\Models\User::ROLE_ADMIN)>Administrador</option>
            <option value="{{ \App\Models\User::ROLE_SUPPLIES_OPERATOR }}" @selected(old('role', $user?->role ?? \App\Models\User::ROLE_ADMIN) === \App\Models\User::ROLE_SUPPLIES_OPERATOR)>Acceso personalizado por modulo</option>
        </select>
    </div>
</div>

<div class="field-grid">
    <div>
        <label for="user_status_{{ $formKey }}">Estado</label>
        <select id="user_status_{{ $formKey }}" name="is_active">
            <option value="1" @selected((string) old('is_active', (int) ($user?->is_active ?? 1)) === '1')>Activo</option>
            <option value="0" @selected((string) old('is_active', (int) ($user?->is_active ?? 1)) === '0')>Inactivo</option>
        </select>
    </div>
    <div></div>
</div>

@php
    $selectedPermissions = old(
        'permissions',
        $user?->permissions ?? ($user ? [] : \App\Models\User::defaultModulePermissions())
    );
@endphp

<div>
    <label>Permisos por modulo y accion</label>
    <div class="permission-grid" data-permissions-panel>
        @foreach ($permissionMatrix as $module => $definition)
            <div class="permission-group">
                <div class="permission-group-title">{{ $definition['label'] }}</div>
                <div class="permission-group-actions">
                    @foreach ($definition['actions'] as $action)
                        @php
                            $permissionValue = \App\Models\User::composePermission($module, $action);
                            $isChecked = in_array($permissionValue, $selectedPermissions, true)
                                || in_array($module, $selectedPermissions, true);
                        @endphp
                        <label class="permission-option">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $permissionValue }}"
                                @checked($isChecked)
                                data-permission-checkbox
                            >
                            <span>{{ \App\Models\User::actionLabel($action) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
    <p class="subtle-note">Administrador recibe acceso total. En acceso personalizado puedes decidir si cada usuario solo ve, crea, edita o elimina.</p>
</div>

<div class="field-grid">
    <div>
        <label for="user_password_{{ $formKey }}">Contraseña {{ $user ? '(dejar vacía para conservar)' : '' }}</label>
        <input id="user_password_{{ $formKey }}" name="password" type="password" {{ $user ? '' : 'required' }} class="@error('password') is-invalid @enderror">
        
    </div>
    <div>
        <label for="user_password_confirmation_{{ $formKey }}">Confirmar contraseña</label>
        <input id="user_password_confirmation_{{ $formKey }}" name="password_confirmation" type="password" {{ $user ? '' : 'required' }}>
    </div>
</div>

<button type="submit"><i class="fa-solid fa-floppy-disk"></i> {{ $submitLabel }}</button>

<script>
    (() => {
        const script = document.currentScript;
        const form = script?.parentElement?.tagName === 'FORM' ? script.parentElement : null;
        const roleField = form?.querySelector('[data-user-role]');
        const permissionsPanel = form?.querySelector('[data-permissions-panel]');
        const permissionCheckboxes = Array.from(form?.querySelectorAll('[data-permission-checkbox]') ?? []);

        if (!roleField || !permissionsPanel || permissionCheckboxes.length === 0) {
            return;
        }

        const syncPermissionState = () => {
            const isAdmin = roleField.value === '{{ \App\Models\User::ROLE_ADMIN }}';
            permissionsPanel.classList.toggle('is-disabled', isAdmin);

            permissionCheckboxes.forEach((checkbox) => {
                checkbox.disabled = isAdmin;
                if (isAdmin) {
                    checkbox.checked = true;
                }
            });
        };

        roleField.addEventListener('change', syncPermissionState);
        syncPermissionState();
    })();
</script>
