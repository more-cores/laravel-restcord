<?php

namespace LaravelRestcord;

use Auth;
use Event;
use Illuminate\Auth\Events\Login;
use LaravelRestcord\Authentication\AddTokenToSession;
use RestCord\DiscordClient;
use LaravelRestcord\Authentication\Socialite\DiscordProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Laravel\Lumen\Application as LumenApplication;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register paths to be published by the publish command.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath($raw = __DIR__.'/../config/laravel-restcord.php') ?: $raw;
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('laravel-restcord.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('laravel-restcord');
        }
        $this->mergeConfigFrom($source, 'laravel-restcord');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // upon login add the token to session if using Discord's socialite
        if (class_exists('SocialiteProviders\Discord\DiscordExtendSocialite')) {
            Event::listen(Login::class, AddTokenToSession::class);
        }

        $this->app->bind(Discord::class, function ($app) {
            return new Discord(session('discord_token'));
        });

        $this->app->bind(DiscordClient::class, function ($app) {
            $config = $app['config']['laravel-restcord'];

            $discordClientConfig = [
                'token' => $config['bot-token'],

                // use Laravel's monologger
                'logger' => $app['log']->getMonolog(),

                'throwOnRatelimit' => $config['throw-exception-on-rate-limit'],
            ];

            // if logged in via Discord via Laravel Socialite, use that token
            if (session()->has('discord_token')) {
                $discordClientConfig['tokenType'] = 'OAuth';
                $discordClientConfig['token'] = session('discord_token');
            }

            return new DiscordClient($discordClientConfig);
        });
    }

    public function provides()
    {
        return [
            DiscordClient::class
        ];
    }
}
