<?php

namespace ChuJC\Admin\Models\Traits;

use Illuminate\Support\Collection;

trait HasPermissionsTrait
{

    /**
     * Check if user has permission.
     *
     * @param $ability
     *
     * @return bool
     */
    public function can($ability): bool
    {
        if ($this->isAdministrator()) {
            return true;
        }
        return $this->allPermissions()->pluck('permission')->contains($ability);
    }


    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator(): bool
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is $role.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function isRole(string $role): bool
    {
        return $this->effectiveRoles->pluck('role_key')->contains($role);
    }

    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public function allPermissions(): Collection
    {
        return $this->effectiveRoles->pluck('menus')->flatten();
    }
}
