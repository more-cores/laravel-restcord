<?php

namespace LaravelRestcord\Authentication;

use Illuminate\Contracts\Session\Session;
use LaravelRestcord\Authentication\Socialite\DiscordProvider;

class AddTokenToSession
{
    /** @var Session */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function handle()
    {
        if (DiscordProvider::$token != null) {
            $this->session->put('discord_token', DiscordProvider::$token);
        }
    }
}
