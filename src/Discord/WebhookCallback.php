<?php

namespace LaravelRestcord\Discord;

use Illuminate\Support\Fluent;
use LaravelRestcord\Discord;

abstract class WebhookCallback extends Fluent
{
    public function callbackUrl() : string
    {
        return Discord::callbackUrl().'/create-webhook';
    }
}
