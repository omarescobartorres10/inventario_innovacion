<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_SUPPLIES_OPERATOR = 'supplies_operator';

    public const PERMISSION_DASHBOARD = 'dashboard';

    public const PERMISSION_ITEMS = 'items';

    public const PERMISSION_MOVEMENTS = 'movements';

    public const PERMISSION_EQUIPMENTS = 'equipments';

    public const PERMISSION_DELIVERY_ACTS = 'delivery_acts';

    public const PERMISSION_USERS = 'users';

    public const PERMISSION_CATALOGS = 'catalogs';

    public const PERMISSION_EXPORTS = 'exports';

    public const ACTION_VIEW = 'view';

    public const ACTION_CREATE = 'create';

    public const ACTION_EDIT = 'edit';

    public const ACTION_DELETE = 'delete';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'position',
        'role',
        'permissions',
        'is_active',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'password' => 'hashed',
        ];
    }

    public static function roleOptions(): array
    {
        return [
            self::ROLE_ADMIN => 'Administrador',
            self::ROLE_SUPPLIES_OPERATOR => 'Acceso personalizado',
        ];
    }

    public static function permissionOptions(): array
    {
        $options = [];

        foreach (self::permissionMatrix() as $module => $definition) {
            foreach ($definition['actions'] as $action) {
                $options[self::composePermission($module, $action)] = $definition['label'].' · '.self::actionLabel($action);
            }
        }

        return $options;
    }

    public static function permissionMatrix(): array
    {
        return [
            self::PERMISSION_DASHBOARD => [
                'label' => 'Dashboard',
                'actions' => [self::ACTION_VIEW],
            ],
            self::PERMISSION_ITEMS => [
                'label' => 'Insumos',
                'actions' => [self::ACTION_VIEW, self::ACTION_CREATE, self::ACTION_EDIT, self::ACTION_DELETE],
            ],
            self::PERMISSION_MOVEMENTS => [
                'label' => 'Movimientos',
                'actions' => [self::ACTION_VIEW, self::ACTION_CREATE],
            ],
            self::PERMISSION_EQUIPMENTS => [
                'label' => 'Equipos',
                'actions' => [self::ACTION_VIEW, self::ACTION_CREATE, self::ACTION_EDIT, self::ACTION_DELETE],
            ],
            self::PERMISSION_DELIVERY_ACTS => [
                'label' => 'Actas',
                'actions' => [self::ACTION_VIEW, self::ACTION_CREATE, self::ACTION_EDIT],
            ],
            self::PERMISSION_USERS => [
                'label' => 'Usuarios',
                'actions' => [self::ACTION_VIEW, self::ACTION_CREATE, self::ACTION_EDIT, self::ACTION_DELETE],
            ],
            self::PERMISSION_CATALOGS => [
                'label' => 'Catalogo',
                'actions' => [self::ACTION_VIEW, self::ACTION_CREATE, self::ACTION_EDIT, self::ACTION_DELETE],
            ],
            self::PERMISSION_EXPORTS => [
                'label' => 'Exportaciones',
                'actions' => [self::ACTION_VIEW],
            ],
        ];
    }

    public static function composePermission(string $module, string $action = self::ACTION_VIEW): string
    {
        return $module.'.'.$action;
    }

    public static function actionLabel(string $action): string
    {
        return match ($action) {
            self::ACTION_VIEW => 'Ver',
            self::ACTION_CREATE => 'Crear',
            self::ACTION_EDIT => 'Editar',
            self::ACTION_DELETE => 'Eliminar',
            default => ucfirst($action),
        };
    }

    public static function defaultModulePermissions(): array
    {
        return array_keys(self::permissionOptions());
    }

    public static function acceptedPermissionValues(): array
    {
        return array_values(array_unique([
            ...array_keys(self::permissionMatrix()),
            ...array_keys(self::permissionOptions()),
        ]));
    }

    public static function normalizePermissions(array $permissions): array
    {
        $normalized = collect($permissions)
            ->filter(fn ($permission) => in_array($permission, self::acceptedPermissionValues(), true))
            ->flatMap(function (string $permission) {
                if (str_contains($permission, '.')) {
                    return [$permission];
                }

                $moduleDefinition = self::permissionMatrix()[$permission] ?? null;

                if (! $moduleDefinition) {
                    return [];
                }

                return collect($moduleDefinition['actions'])
                    ->map(fn (string $action) => self::composePermission($permission, $action))
                    ->all();
            })
            ->unique()
            ->values()
            ->all();

        return $normalized;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permissions = self::normalizePermissions($this->permissions ?? []);

        if (in_array($permission, $permissions, true)) {
            return true;
        }

        if (! str_contains($permission, '.')) {
            return in_array($permission, $permissions, true)
                || in_array(self::composePermission($permission, self::ACTION_VIEW), $permissions, true);
        }

        [$module] = explode('.', $permission, 2);

        return in_array($module, $permissions, true);
    }

    public function canAccessModule(string $module): bool
    {
        return $this->hasPermission(self::composePermission($module, self::ACTION_VIEW));
    }

    public function canCreateIn(string $module): bool
    {
        return $this->hasPermission(self::composePermission($module, self::ACTION_CREATE));
    }

    public function canEditIn(string $module): bool
    {
        return $this->hasPermission(self::composePermission($module, self::ACTION_EDIT));
    }

    public function canDeleteIn(string $module): bool
    {
        return $this->hasPermission(self::composePermission($module, self::ACTION_DELETE));
    }

    public function defaultLandingRoute(): string
    {
        if ($this->isAdmin() || $this->canAccessModule(self::PERMISSION_DASHBOARD)) {
            return 'inventory.dashboard';
        }

        if ($this->canAccessModule(self::PERMISSION_ITEMS)) {
            return 'inventory.items.index';
        }

        if ($this->canAccessModule(self::PERMISSION_DELIVERY_ACTS)) {
            return 'inventory.delivery-act.form';
        }

        if ($this->canAccessModule(self::PERMISSION_EQUIPMENTS)) {
            return 'equipments.index';
        }

        if ($this->canAccessModule(self::PERMISSION_MOVEMENTS)) {
            return 'inventory.movements.index';
        }

        if ($this->canAccessModule(self::PERMISSION_CATALOGS)) {
            return 'catalogs.equipment-categories.index';
        }

        if ($this->canAccessModule(self::PERMISSION_USERS)) {
            return 'users.index';
        }

        return 'inventory.items.index';
    }

    public function permissionLabels(): array
    {
        $labels = collect(self::normalizePermissions($this->permissions ?? []))
            ->map(function (string $permission) {
                return self::permissionOptions()[$permission] ?? $permission;
            })
            ->values()
            ->all();

        return $labels;
    }
}
