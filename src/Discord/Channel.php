<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;
use LaravelRestcord\Discord;

class Channel extends Fluent
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

    public function id() : int
    {
        return $this->id;
    }

    public function guildId() : int
    {
        return $this->guild_id;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function position() : int
    {
        return $this->position;
    }

    public function topic() : string
    {
        return $this->topic;
    }

    public function isVoice() : bool
    {
        return $this->type == 'voice';
    }

    public function isText() : bool
    {
        return $this->type == 'text';
    }

    public function getById(int $id) : self
    {
        $channelData = $this->api->get('/channels/'.$id);

        return new self($channelData, $this->api);
    }
}
