<?php

namespace LaravelRestcord\Discord;

use LaravelRestcord\Discord\Webhooks\TooManyWebhooks;
use LaravelRestcord\Exception;

class ErrorFactory
{
    public function make(int $code, string $message) : ?Exception
    {
        if ($code == TooManyWebhooks::ID) {
            return new TooManyWebhooks($message, $code);
        }

        return null;
    }
}
