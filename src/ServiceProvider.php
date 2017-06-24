<?php

namespace LaravelRestcord;

use Event;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Routing\Router;
use Laravel\Lumen\Application as LumenApplication;
use LaravelRestcord\Authentication\AddTokenToSession;
use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Http\Middleware\InstantiateApiClientWithTokenFromSession;
use LaravelRestcord\Http\WebhookCallback;
use RestCord\DiscordClient;

/**
 * @codeCoverageIgnore
 *
 * We don't test service providers because it would require pulling the entire Laravel framework
 * in this repo which is a bit of overkill.
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register paths to be published by the publish command.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $source = realpath($raw = __DIR__.'/../config/laravel-restcord.php') ?: $raw;
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('laravel-restcord.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('laravel-restcord');
        }
        $this->mergeConfigFrom($source, 'laravel-restcord');

        // Use our middleware to configure the ApiClient to be bootstrapped with
        // the Discord token
        $router->aliasMiddleware('sessionHasDiscordToken', InstantiateApiClientWithTokenFromSession::class);
        $router->group([
            'middleware' => 'sessionHasDiscordToken'
        ], function () use ($router) {
            $router->get('/discord/create-webhook', WebhookCallback::class.'@createWebhook');
        });
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        Discord::setKey(env('DISCORD_KEY', ''));
        Discord::setCallbackUrl(env('APP_URL', ''));

        // upon login add the token to session if using Discord's socialite
        if (class_exists('SocialiteProviders\Discord\DiscordExtendSocialite')) {
            Event::listen(Login::class, AddTokenToSession::class);
        }

        $this->app->bind(ApiClient::class, function ($app) {
            return new ApiClient(session('discord_token'));
        });

        // Configure Restcord's DiscordClient with some laravel components
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
            ApiClient::class,
            DiscordClient::class,
        ];
    }
}
