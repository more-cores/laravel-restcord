<?php

namespace LaravelRestcord;

use RestCord\DiscordClient;

class Discord extends DiscordClient
{
    public function __construct(string $token, array $configuration = [])
    {
        $configuration['token'] = $token;
        $configuration['logger'] = app('log')->getMonolog();

        parent::__construct($configuration);
    }
}
