# laravel-restcord [![Latest Stable Version](https://poser.pugx.org/more-cores/laravel-restcord/v/stable.png)](https://packagist.org/packages/more-cores/laravel-restcord) [![Total Downloads](https://poser.pugx.org/more-cores/laravel-restcord/downloads.png)](https://packagist.org/packages/more-cores/laravel-restcord) [![Coverage Status](https://coveralls.io/repos/github/more-cores/laravel-restcord/badge.svg?branch=master)](https://coveralls.io/github/more-cores/laravel-restcord?branch=master)

Small wrapper for [Restcord](http://www.restcord.com).  

# Features
 
 * Integrates Restcord with [Laravel Socialite](http://socialiteproviders.github.io) so currently OAuth'd user is used for api calls

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