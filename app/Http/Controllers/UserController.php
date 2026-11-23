<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'summary' => app(InventoryController::class)->summary(),
            'users' => User::query()->orderByDesc('id')->paginate(20),
            'roles' => User::roleOptions(),
            'permissions' => User::permissionOptions(),
            'permissionMatrix' => User::permissionMatrix(),
            'editingUser' => request('edit')
                ? User::query()->find(request('edit'))
                : null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => $this->usernameRules(),
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'digits:8'],
            'position' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(array_keys(User::roleOptions()))],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(User::acceptedPermissionValues())],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            // 👇 MENSAJES PERSONALIZADOS 👇
            'name.required' => 'El nombre es obligatorio.',
            'username.required' => 'El nombre de usuario es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'phone.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'role.required' => 'El tipo de acceso es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ] );

        $permissions = $validated['role'] === User::ROLE_ADMIN
            ? User::defaultModulePermissions()
            : User::normalizePermissions($validated['permissions'] ?? []);
            
        $username = Str::lower(trim($validated['username']));

        User::query()->create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'] ?? null,
            'role' => $validated['role'],
            'permissions' => $permissions,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $authUser = Auth::user();

        // 🛡️ PROTECCIÓN: Solo un admin puede modificar a otro admin
        if ($user->isAdmin() && ! $authUser->isAdmin()) {
            return back()->withErrors([
                'permission_error' => 'No tienes permisos para modificar a un administrador.',
            ])->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => $this->usernameRules($user),
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'digits:8'],
            'position' => ['nullable', 'string', 'max:255'],
            'role' => ['required', Rule::in(array_keys(User::roleOptions()))],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(User::acceptedPermissionValues())],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            // 👇 MENSAJES PERSONALIZADOS 👇
            'name.required' => 'El nombre es obligatorio.',
            'username.required' => 'El nombre de usuario es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'email.unique' => 'Este correo ya está registrado en el sistema.',
            'username.unique' => 'Este nombre de usuario ya está en uso.',
            'phone.digits' => 'El teléfono debe tener exactamente 8 dígitos.',
            'role.required' => 'El tipo de acceso es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ] );

        // 🛡️ PROTECCIÓN: Evitar que el admin se desactive o se quite el rol a sí mismo
        if (Auth::id() === $user->id) {
            // Si está intentando desactivarse
            if (!($validated['is_active'] ?? false)) {
                return back()->withErrors([
                    'permission_error' => 'No puedes desactivar tu propio usuario mientras estás autenticado.',
                ])->withInput();
            }
            
            // Si está intentando quitarse el rol de admin
            if ($validated['role'] !== \App\Models\User::ROLE_ADMIN) {
                return back()->withErrors([
                    'permission_error' => 'No puedes quitarte el rol de administrador a ti mismo.',
                ])->withInput();
            }
        }

        // 🛡️ PROTECCIÓN: No dejar el sistema sin admins
        if ($user->isAdmin() && $validated['role'] !== User::ROLE_ADMIN) {
            $adminCount = User::query()->where('role', User::ROLE_ADMIN)->count();
            if ($adminCount <= 1) {
                return back()->withErrors([
                    'permission_error' => 'No se puede quitar el rol de administrador. Debe haber al menos un admin en el sistema.',
                ])->withInput();
            }
        }

        
        $permissions = $validated['role'] === User::ROLE_ADMIN
            ? User::defaultModulePermissions()
            : User::normalizePermissions($validated['permissions'] ?? []);
        $username = Str::lower(trim($validated['username']));

        $payload = [
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'position' => $validated['position'] ?? null,
            'role' => $validated['role'],
            'permissions' => $permissions,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $authUser = Auth::user();

        // 🛡️ PROTECCIÓN: Solo un admin puede eliminar a otro admin
        if ($user->isAdmin() && ! $authUser->isAdmin()) {
            return redirect()
                ->route('users.index')
                ->withErrors(['user' => 'No tienes permisos para eliminar a un administrador.']);
        }

        // 🛡️ PROTECCIÓN: No eliminar al último admin
        if ($user->isAdmin()) {
            $adminCount = User::query()->where('role', User::ROLE_ADMIN)->count();
            if ($adminCount <= 1) {
                return redirect()
                    ->route('users.index')
                    ->withErrors(['user' => 'No se puede eliminar al último administrador del sistema.']);
            }
        }

        if (Auth::id() === $user->id) {
            return redirect()
                ->route('users.index')
                ->withErrors(['user' => 'No puedes eliminar tu propio usuario mientras estas autenticado.']);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('status', 'Usuario eliminado correctamente.');
    }

    private function usernameRules(?User $user = null): array
    {
        $rules = [
            'required',
            'string',
            'min:3',
            'max:30',
            'regex:/^[a-z0-9]+(?:[._-][a-z0-9]+)*$/i',
        ];

        $uniqueRule = Rule::unique('users', 'username');

        if ($user) {
            $uniqueRule->ignore($user->id);
        }

        $rules[] = $uniqueRule;

        return $rules;
    }
}
