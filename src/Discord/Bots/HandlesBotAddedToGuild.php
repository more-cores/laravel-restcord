<?php

namespace LaravelRestcord\Discord\Bots;

use Exception;
use Illuminate\Http\RedirectResponse;
use LaravelRestcord\Discord\Guild;

trait HandlesBotAddedToGuild
{
    /**
     * After adding the bot to a guild, your user will be redirected back to a controller
     * that'll interpret the response and call this method, empowering you to control
     * what happens next.
     */
    abstract public function botAdded(Guild $guild) : RedirectResponse;

    /**
     * When an error occurs when adding a bot the exception will be passed here.
     *
     * For a list of common error scenarios you should handle,
     * see LaravelRestcord\Discord\ErrorFactory
     */
    public function errored(Exception $exception)
    {
        throw $exception;
    }
}
