<?php

namespace LaravelRestcord\Discord\Permissions;

trait ChecksPermissions
{
    /**
     * Determine if the entity in question has the necessary
     * permissions.
     */
    public function can(CanHavePermissions $hasPermissions, int $permission) : bool
    {
        if ($hasPermissions->hasPermission(Permission::ADMINISTRATOR)) {
            return true;
        }

        return $hasPermissions->hasPermission($permission);
    }
}
