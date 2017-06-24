<?php

namespace LaravelRestcord\Discord\Webhooks;

use LaravelRestcord\Exception;

class TooManyWebhooks extends Exception
{
    public const ID = 30007;
}
