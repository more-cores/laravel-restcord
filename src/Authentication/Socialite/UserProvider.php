<?php

namespace LaravelRestcord\Authentication\Socialite;

use Illuminate\Events\Dispatcher;
use Laravel\Socialite\Contracts\Factory;
use LaravelRestcord\Authentication\LoginWithDiscord;
use LaravelRestcord\Authentication\Token;
use SocialiteProviders\Manager\OAuth2\User;

class UserProvider
{
    /** @var Factory */
    protected $socialite;

    /** @var Dispatcher */
    protected $event;

    /** @var User */
    protected $user;

    /** @var Token */
    protected $token;

    public function __construct(Factory $socialite, Dispatcher $event)
    {
        $this->socialite = $socialite;
        $this->event = $event;
    }

    public function discordUser() : User
    {
        if ($this->user == null) {
            $this->fetchUser();
        }

        return $this->user;
    }

    public function token() : Token
    {
        if ($this->user == null) {
            $this->fetchUser();
        }

        return $this->token;
    }

    protected function fetchUser()
    {
        $this->user = $this->socialite->driver('discord')->user();

        $this->token = new Token();
        $this->token->setToken($this->user->token);
        $this->token->setRefreshToken($this->user->refreshToken);
        $this->token->setExpiresIn($this->user->expiresIn);

        $this->event->fire(new LoginWithDiscord($this->user, $this->token));
    }
}
