# laravel-restcord [![Latest Stable Version](https://poser.pugx.org/more-cores/laravel-restcord/v/stable.png)](https://packagist.org/packages/more-cores/laravel-restcord) [![Total Downloads](https://poser.pugx.org/more-cores/laravel-restcord/downloads.png)](https://packagist.org/packages/more-cores/laravel-restcord)

Small wrapper for [Restcord](http://www.restcord.com).

# Features
 
 * Integrates Restcord with Laravel Socialite so currently OAuth'd user is used for api calls

# Installation

```
composer require more-cores/laravel-restcord:dev-master
```

Register the [service provider](http://laravel.com/docs/master/providers) in `config/app.php`

```php
'providers' => [
    ...
    LaravelRestcord\ServiceProvider::class,
]
```

Define the `DISCORD_BOT_TOKEN` environmental variable.