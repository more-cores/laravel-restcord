<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;
use LaravelRestcord\Discord;
use LaravelRestcord\Discord\Permissions\CanHavePermissions;

class Role extends Fluent implements CanHavePermissions
{
    use Discord\Permissions\HasPermissions;
    use Discord\Permissions\ChecksPermissions;

    /** @var ApiClient */
    protected $api;

    public function __construct(array $attributes = [], ?ApiClient $apiClient = null)
    {
        parent::__construct($attributes);

        if ($apiClient == null) {
            $apiClient = Discord::client();
        }
        $this->api = $apiClient;
    }

    public function id() : int
    {
        return $this->id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function color() : int
    {
        return $this->color;
    }

    public function isHoisted() : bool
    {
        return (bool) $this->hoist;
    }

    /**
     * @see https://discordapi.com/permissions.html
     */
    public function permissions() : int
    {
        return (int) $this->permissions;
    }

    public function mentionable() : bool
    {
        return (bool) $this->mentionable;
    }
}
