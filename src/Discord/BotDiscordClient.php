<?php

namespace LaravelRestcord\Discord;

use RestCord\DiscordClient;

class BotDiscordClient extends DiscordClient
{
    // This client only exists here to act as an IOC binding for using the application's
    // bot token rather than the user's oauth token.
    // See readme for details
}