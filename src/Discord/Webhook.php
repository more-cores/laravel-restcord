<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;

class Webhook extends Fluent
{
    /** @var ApiClient */
    protected $api;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function id() : int
    {
        return $this->id;
    }

    public function guildId() : int
    {
        return $this->guild_id;
    }

    public function channelId() : int
    {
        return $this->channel_id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function token() : string
    {
        return $this->token;
    }
}
