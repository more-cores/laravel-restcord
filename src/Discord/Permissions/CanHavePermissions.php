<?php

namespace LaravelRestcord\Discord\Permissions;

interface CanHavePermissions
{
    /**
     * Determine if an entity has a given permission.
     */
    public function hasPermission(int $permission) : bool;
}
