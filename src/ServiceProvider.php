<?php

namespace LaravelRestcord;

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

        $this->app->bind(Discord::class, function ($app) {
            $config = $app['config']['laravel-restcord'];

            return new Discord($config['bot-token'], [
                'throwOnRatelimit' => $config['throw-exception-on-rate-limit'],
            ]);
        });
    }
}
