<?php

namespace Modules\Acl\Utils;

use Illuminate\Support\Arr;
use Modules\Acl\Services\AclService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AclBuilder
{
    protected string $defaultAdminRole = 'admin';

    private array $permissions = [];

    private ?string $module;

    private bool $exceptAdmin = false;

    private array $attachOnly = [];

    private array $guards = [];

    public function __construct()
    {
        $this->defaultAdminRole = AclService::adminRole();
        $this->reset();
    }

    public function withGuards(array|string $guards): static
    {
        $this->guards = collect($this->guards)
            ->merge($this->forceArray($guards))
            ->unique()
            ->toArray();

        return $this;
    }

    public function withSanctumGuard(): static
    {
        return $this->withGuards('sanctum');
    }

    public function onlySanctumGuard(): static
    {
        return $this->forGuards('sanctum');
    }

    public function onlyWebGuard(): static
    {
        return $this->forGuards('web');
    }

    public function forGuards(array|string|null $guards): static
    {
        if (! empty($guards)) {
            $this->guards = $this->forceArray($guards);
        }

        return $this;
    }

    public function forModule(string|null $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function exceptAdmin(): static
    {
        $this->exceptAdmin = true;

        return $this;
    }

    public function attachEnum(string|array $permissions, string|array $onlyPermissions = null): static
    {
        return $this->attach($this->formatEnumPermissions($permissions), $onlyPermissions);
    }

    public function attach(string|array $permissions, string|array $onlyPermissions = null): static
    {
        $this->permissions = array_merge($this->permissions, $this->forceArray($permissions));
        if (! empty($onlyPermissions)) {
            $this->attachOnly = array_merge($this->attachOnly, $this->forceArray($onlyPermissions));
        }

        return $this;
    }

    public function create(string|array $moduleRoles = null): static
    {
        $this->createPermissions($this->permissions, $this->module);

        $roles = $this->forceArray($moduleRoles);

        if (! $this->exceptAdmin) {
            $roles[] = $this->defaultAdminRole;
        }

        $permissions = empty($this->attachOnly) ? $this->permissions : $this->attachOnly;
        $this->attachPermissionsToRoles($roles, $permissions);

        $this->reset();

        return $this;
    }

    public function dump(): static
    {
        dump([
            'module' => $this->module,
            'except_admin' => $this->exceptAdmin,
            'permissions' => $this->permissions,
            'attach_only' => $this->attachOnly,
            'guards' => $this->guards,
        ]);

        return $this;
    }

    private function reset(): void
    {
        $this->module = null;
        $this->permissions = [];
        $this->attachOnly = [];
        $this->forGuards(array_keys(config('auth.guards', [])));
    }

    public function createPermissions(array $permissions, string $module = null): void
    {
        foreach ($permissions as $permission) {
            foreach ($this->guards as $guard) {
                Permission::firstOrCreate(['name' => $permission, 'module' => $module, 'guard_name' => $guard]);
            }
        }
    }

    private function forceArray(string|array|null $value): array
    {
        if (empty($value)) {
            return [];
        }

        return is_array($value) ? $value : [$value];
    }

    public function formatEnumPermissions(string|array $enumPermissions): array
    {
        return collect($this->forceArray($enumPermissions))
            ->flatMap(fn ($enum) => Arr::pluck($enum::cases(), 'value'))
            ->toArray();
    }

    public function attachPermissionsToRoles(array $roles, array|string $permissions): void
    {
        if (empty($roles)) {
            return;
        }

        foreach ($roles as $role) {
            foreach ($this->guards as $guard) {
                Role::firstOrCreate(['name' => $role], ['guard_name' => $guard])->givePermissionTo($permissions);
            }
        }
    }
}
