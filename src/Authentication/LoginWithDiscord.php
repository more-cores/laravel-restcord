<?php

namespace LaravelRestcord\Authentication;

use Laravel\Socialite\Two\User;

class LoginWithDiscord
{
    /** @var User */
    protected $user;

    /** @var Token */
    protected $token;

    public function __construct(User $user, Token $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function user() : User
    {
        return $this->user;
    }

    public function token() : Token
    {
        return $this->token;
    }
}