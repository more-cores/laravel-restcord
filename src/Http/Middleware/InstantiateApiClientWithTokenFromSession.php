<?php

namespace LaravelRestcord\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Session\Session;
use LaravelRestcord\Discord;
use LaravelRestcord\Discord\ApiClient;

class InstantiateApiClientWithTokenFromSession
{
    /** @var Session */
    protected $session;

    /** @var Application */
    protected $app;

    public function __construct(Session $session, Application $app)
    {
        $this->session = $session;
        $this->app = $app;
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->session->has('discord_token')) {
            Discord::setClient($this->app->make(ApiClient::class));
        }

        return $next($request);
    }
}
