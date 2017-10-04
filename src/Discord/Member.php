<?php

namespace LaravelRestcord\Discord;

use Carbon\Carbon;
use Illuminate\Support\Fluent;
use LaravelRestcord\Discord;

class Member extends Fluent
{
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

    /**
     * @return Role[]
     */
    public function roles() : array
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = new Role($role, $this->api);
        }

        return $roles;
    }

    public function joinedAt() : Carbon
    {
        return new Carbon($this->joined_at);
    }

    public function isDeaf() : bool
    {
        return (bool) $this->deaf;
    }

    public function isMute() : bool
    {
        return (bool) $this->mute;
    }
}
