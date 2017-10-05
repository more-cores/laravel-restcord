<?php

namespace LaravelRestcord\Discord\Permissions;

trait HasPermissions
{
    /**
     * Provides the permissions to be checked against.
     */
    abstract public function permissions() : int;

    /**
     * Determine if an entity has a given permission.
     */
    public function hasPermission(int $permission) : bool
    {
        return $permission & $this->permissions();
    }
}
