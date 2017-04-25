<?php

namespace LaravelRestcord;

use RestCord\DiscordClient;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register paths to be published by the publish command.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/laravel-restcord.php' => config_path('laravel-restcord.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/laravel-restcord.php', 'laravel-restcord'
        );

        $this->app->bind(DiscordClient::class, function ($app) {
            $config = $app['config']['laravel-restcord'];

            return new DiscordClient([
                'token' => $app['bot-token'],

                // use Laravel's monologger
                'logger' => $app['log']->getMonolog(),

                'throwOnRatelimit' => $config['throw-exception-on-rate-limit'],
            ]);
        });
    }
}
