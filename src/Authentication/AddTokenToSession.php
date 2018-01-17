<?php

namespace LaravelRestcord\Authentication;

use Illuminate\Contracts\Session\Session;
use Laravel\Socialite\Contracts\Factory;

class AddTokenToSession
{
    /** @var Session */
    protected $session;

    /** @var Factory */
    protected $socialite;

    public function __construct(Session $session, Factory $socialite)
    {
        $this->session = $session;
        $this->socialite = $socialite;
    }

    public function handle(LoginWithDiscord $loginWithDiscord)
    {
        $this->session->put('discord_token', $loginWithDiscord->token()->toArray());
    }
}
