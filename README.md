# laravel-restcord [![Latest Stable Version](https://poser.pugx.org/more-cores/laravel-restcord/v/stable.png)](https://packagist.org/packages/more-cores/laravel-restcord) [![Total Downloads](https://poser.pugx.org/more-cores/laravel-restcord/downloads.png)](https://packagist.org/packages/more-cores/laravel-restcord) [![Coverage Status](https://coveralls.io/repos/github/more-cores/laravel-restcord/badge.svg?branch=code-coverage)](https://coveralls.io/github/more-cores/laravel-restcord)

Small wrapper for [Restcord](http://www.restcord.com).  

# Features
 
 * Integrates Restcord with [Laravel Socialite](http://socialiteproviders.github.io) so currently OAuth'd user is used for api calls
 * Allows creation of webhooks via OAuth (no bot required)

# Installation

 1. Install package

```
composer require more-cores/laravel-restcord:dev-master
```

 2. Register the [service provider](http://laravel.com/docs/master/providers) in `config/app.php`

```php
'providers' => [
    ...
    LaravelRestcord\ServiceProvider::class,
]
```

 3. Define the `DISCORD_BOT_TOKEN` environmental variable.
 4. Add the middleware `sessionHasDiscordToken` for the routes where you need to use the current OAuth'd user's credentials to interact with the Discord API.  This is required because session information is not available in a ServiceProvider.
  
# Usage


## Creating Webhooks

Extend the webhook callback:

```php
use LaravelRestcord\Discord\WebhookCallback;

class Subscribe extends WebhookCallback
{

}
```

In your controller, send the user to Discord to create the webhook in their UI:

```php
    public function show(Guild $guild)
    {
        $guild->createWebhook(new Subscribe());
    }
```

They'll then be prompted to choose a guild and channel for the webhook.  Listen for the event so you can save the webhook token when the user is redirected back to your application:

```php
    protected $listen = [
        LaravelRestcord\Discord\WebhookCreated::class => [
            // save the webhook token
        ],
    ];

```