<?php

namespace LaravelRestcord\Authentication;

use LaravelRestcord\Discord;

trait InteractsWithDiscordAsBot
{
    /**
     * The current request will now act as our bot when interacting
     * with the Discord API.
     */
    public function useDiscordBotToken()
    {
        Discord::setKey(env('DISCORD_BOT_KEY', env('DISCORD_KEY', '')));
        Discord::setSecret(env('DISCORD_BOT_SECRET', env('DISCORD_SECRET', '')));
    }
}
