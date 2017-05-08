<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Guild;

class Discord
{
    /** @var ApiClient */
    protected $api;

    public function __construct(ApiClient $apiClient)
    {
        $this->api = $apiClient;
    }

    /**
     * Guilds the current user has access to.  This is an abbreviated
     * version of the guilds endpoint so limited fields are provided.
     *
     * @see https://discordapp.com/developers/docs/resources/user#user-guild-object
     *
     * @return array
     */
    public function guilds() : array
    {
        $listOfGuilds = $this->api->get('https://discordapp.com/api/users/@me/guilds');

        $guilds = [];
        foreach ($listOfGuilds as $guildData) {
            $guilds[] = new Guild($guildData);
        }

        return $guilds;
    }
}
