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
     *
     * If you intend to interact with this guild via your bot, you don't *need* to keep the
     * tokens, but you may want to consider storing them anyways in case you ever want to
     * interact with this guild using oauth instead of your bot token.
     */
    abstract public function botAdded(string $accessToken, int $expiresIn, string $refreshToken, Guild $guild) : RedirectResponse;

    /**
     * If the user hits cancel, we'll need to handle the error.  Usually
     * $error = "access_denied".
     */
    abstract public function botNotAdded(string $error) : RedirectResponse;

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
