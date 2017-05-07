<?php

namespace LaravelRestcord;

use GuzzleHttp\Client;
use LaravelRestcord\Discord\Guild;

class Discord
{
    /** @var string */
    protected $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Guilds the current user has access to
     *
     * @return array
     */
    public function guilds() : array
    {
        $response = $this->client()->get('https://discordapp.com/api/users/@me/guilds');

        $json = json_decode($response->getBody()->getContents(), true);

        $guilds = [];
        foreach ($json as $guild) {
            $guilds[] = new Guild($guild);
        }

        return $guilds;
    }

    public function client() : Client
    {
        return new Client(
            [
                'headers'     => [
                    'Authorization' => 'Bearer '.$this->token(),
                    'User-Agent'    => "LaravelRestcord (https://github.com/more-cores/laravel-restcord)",
                    'Content-Type'  => 'application/json',
                ],
            ]
        );
    }

    public function token() : string
    {
        return $this->token;
    }
}