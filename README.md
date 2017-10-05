# laravel-restcord [![Latest Stable Version](https://poser.pugx.org/more-cores/laravel-restcord/v/stable.png)](https://packagist.org/packages/more-cores/laravel-restcord) [![Total Downloads](https://poser.pugx.org/more-cores/laravel-restcord/downloads.png)](https://packagist.org/packages/more-cores/laravel-restcord) [![Coverage Status](https://coveralls.io/repos/github/more-cores/laravel-restcord/badge.svg)](https://coveralls.io/github/more-cores/laravel-restcord)

A small, fluent wrapper for [Restcord](http://www.restcord.com).  

## README Contents

* [Features](#features)
* [Installation](#installation)
* [Usage](#usage)
  * [Guilds](#guilds)
  * [Adding Bots To Guilds](#adding-bots-to-guilds)
  * [Creating Webhooks](#creating-webhooks)

<a name="features" />

# Features
 
 * Integrates Restcord with [Laravel Socialite](http://socialiteproviders.github.io) so currently OAuth'd user is used for api calls
 * Handles creation of webhooks via OAuth (no bot required)
 * Handles adding bots to to guilds via OAuth (no websocket connection required)
 * Obtain information about a member's relationship with a guild (roles, permissions, etc.)

<a name="installation" />

# Installation

 1. Install package

```
composer require more-cores/laravel-restcord:1.*
```

 2. Define the `DISCORD_BOT_TOKEN` environmental variable.
 3. Add the middleware `sessionHasDiscordToken` for the routes where you need to use the current OAuth'd user's credentials to interact with the Discord API.  This is required because session information is not available in a ServiceProvider.


 4. For Laravel <= 5.4, register the [service provider](http://laravel.com/docs/master/providers) in `config/app.php`

```php
'providers' => [
    ...
    LaravelRestcord\ServiceProvider::class,
]
```

<a name="usage" />

# Usage

Anytime you see `$discord` in this documentation it is assumed to be an instance of `LaravelRestcord\Discord\Discord::class` which is available from Laravel's IOC container.

<a name="guilds" />

## Guilds

Get a list of guilds the current user has access to:

```php
$discord->guilds() // Guild[]
```

Get information about a user's relationship with a guild

```php
$guild->userCan(Permission::KICK_MEMBERS); // bool - uses permissions of the currently oauth'd user

$member = $guild->getMemberById($discordUserId); // \LaravelRestcord\Discord\Member
$member->roles(); // \LaravelRestcord\Discord\Role[]
$member->joinedAt(); // Carbon
```

<a name="adding-bots-to-guilds" />

## Adding Bots To Guilds

This implementation uses the [Advanced Both Authorization](https://discordapp.com/developers/docs/topics/oauth2#advanced-bot-authorization) flow to add the bot to a guild.  You should have the **Require OAuth2 Code Grant** option _enabled_ on your app's settings.   

```php
use LaravelRestcord\Discord\HandlesBotAddedToGuild;
use Illuminate\Http\RedirectResponse;

class BotAddedToDiscordGuild
{
    use HandlesBotAddedToGuild;
    
    public function botAdded(Guild $guild) : RedirectResponse
    {
        // do something with the guild information the bot was added to
        
        return redirect('/to/this/page');
    }
}
```

Next, add a binding to your `AppServiceProvider` so the package knows which class to pass the guild information to when the user returns to your web site.

```shell
 $this->app->bind(HandlesBotAddedToGuild::class, BotAddedToDiscordGuild::class);
```

Now you're ready to direct the user to Discord's web site so they can select the guild to add the bot to:

```php
    public function show(Guild $guild)
    {
        // Reference https://discordapi.com/permissions.html to determine
        // the permissions your bot needs
    
        $guild->sendUserToDiscordToAddBot($permissions);
    }
```

This package handles the routing needs, but you need to whitelist the callback URL for this to work.  Add `http://MY-SITE.com/discord/bot-added` to your [application's redirect uris](https://discordapp.com/developers/applications/me).

Your handler will be trigger when the bot has been added to a guild.

 > You will be able to send messages via this bot once it has established a web socket connection.  It only has to do this once, so it's a common practice to use the below code snippet to do so:

```js
"use strict";
var TOKEN="PUT YOUR TOKEN HERE";
fetch("https://discordapp.com/api/v7/gateway").then(function(a){return a.json()}).then(function(a){var b=new WebSocket(a.url+"/?encoding=json&v=6");b.onerror=function(a){return console.error(a)},b.onmessage=function(a){try{var c=JSON.parse(a.data);0===c.op&&"READY"===c.t&&(b.close(),console.log("Successful authentication! You may now close this window!")),10===c.op&&b.send(JSON.stringify({op:2,d:{token:TOKEN,properties:{$browser:"b1nzy is a meme"},large_threshold:50}}))}catch(a){console.error(a)}}});
```

<a name="creating-webhooks" />

## Creating Webhooks

Because we're using OAuth to create webhooks, the user will be directed to Discord's web site to select the guild/channel.  This package handles interpreting the request/response lifecycle for this, so all you need to do is build a handler: 

```php
use LaravelRestcord\Discord\HandlesDiscordWebhooksBeingCreated;
use Illuminate\Http\RedirectResponse;

class Subscribe
{
    use HandlesDiscordWebhooksBeingCreated;
    
    public function webhookCreated(Webhook $webhook) : RedirectResponse
    {
        // $webhook->token();
        // Here you should save the token for use later when activating the webhook
        
        return redirect('/to/this/page');
    }
}
```

Next, add a binding to your `AppServiceProvider` so the package knows which class to pass the webhook data to when the user returns to your web site.

```shell
 $this->app->bind(HandlesDiscordWebhooksBeingCreated::class, DiscordChannelAdded::class);
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

This package handles the routing needs, but you need to whitelist the callback URL for this to work.  Add `http://MY-SITE.com/discord/create-webhook` to your [application's redirect uris](https://discordapp.com/developers/applications/me). 

Your handler will be trigger when the webhook is created.
