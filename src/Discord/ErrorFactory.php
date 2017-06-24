<?php

namespace LaravelRestcord\Discord;

use LaravelRestcord\Discord\Webhooks\TooManyWebhooks;
use LaravelRestcord\Exception;

class ErrorFactory
{
    public function make(int $code, string $message) : ?Exception
    {
        if ($code == 30007) {
            return new TooManyWebhooks($message, $code);
        }
    }
}
