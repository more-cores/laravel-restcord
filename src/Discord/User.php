<?php

namespace LaravelRestcord\Discord;

use LaravelRestcord\Discord;

class User
{
    /** @var Discord */
    protected $discord;

    public function __construct(Discord $discord)
    {
        $this->discord = $discord;
    }

    /**
     * Get a user's guilds they have access to.
     *
     * @return Guild[]
     */
    public function guilds() : array
    {
        $response = $this->discord->client()->get('https://discordapp.com/api/users/@me/guilds');

        $json = json_decode($response->getBody()->getContents(), true);

        $guilds = [];
        foreach ($json as $guild) {
            $guilds[] = new Guild($guild);
        }

        return $guilds;
    }
}
