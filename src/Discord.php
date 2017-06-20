<?php

namespace LaravelRestcord;

use Illuminate\Support\Collection;
use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Guild;

class Discord
{
    /** @var ApiClient */
    public static $api;

    /** @var string */
    public static $key;

    /** @var string */
    public static $callbackUrl;

    public function __construct(?ApiClient $apiClient = null)
    {
        if ($apiClient != null) {
            self::$api = $apiClient;
        }
    }

    /**
     * Guilds the current user has access to.  This is an abbreviated
     * version of the guilds endpoint so limited fields are provided.
     *
     * @see https://discordapp.com/developers/docs/resources/user#user-guild-object
     *
     * @return array
     */
    public function guilds() : Collection
    {
        $listOfGuilds = self::$api->get('/users/@me/guilds');

        $guilds = [];
        foreach ($listOfGuilds as $guildData) {
            $guilds[] = new Guild($guildData);
        }

        return new Collection($guilds);
    }

    /**
     * Maintaining static accessibility on the client allows us to use this throughout
     * other classes in the package without constantly passing around the dependency.
     */
    public static function client() : ApiClient
    {
        return self::$api;
    }

    public static function setClient(ApiClient $apiClient)
    {
        self::$api = $apiClient;
    }

    public static function setKey(string $key)
    {
        self::$key = $key;
    }

    public static function key() : string
    {
        return self::$key;
    }

    public static function setCallbackUrl(string $callbackUrl)
    {
        self::$callbackUrl = $callbackUrl;
    }

    public static function callbackUrl() : string
    {
        return self::$callbackUrl.'/discord';
    }
}
