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

Because we're using OAuth to create webhooks, the user will be directed to Discord's web site to select the guild/channel.  This package handles interpreting the request/response lifecycle for this, so all you need to do is build a handler: 

```php
use LaravelRestcord\Discord\HandlesDiscordWebhooksBeingCreated;

use Illuminate\Http\Response;

class Subscribe
{
    use HandlesDiscordWebhooksBeingCreated;
    
    public function webhookCreated(Webhook $webhook) : Response
    {
        // $webhook->token();
        // Here you should save the token for use later when activating the webhook
    }
}
```

Next, configure the package to use your handler by publishing the config and configuring the `webhook-created-handler`.

```shell
 $ php artisan vendor:publish --provider=LaravelRestcord\ServiceProvider
```

Now you're ready to direct the user to Discord's web site to create the webhook:

```php
    public function show(Guild $guild)
    {
        // redirects the user to Discord's interface for selecting
        // a guild and channel for the webhook
        $guild->sendUserToDiscordToCreateWebhook();
    }
```

Your handler will be trigger when the webhook is created.