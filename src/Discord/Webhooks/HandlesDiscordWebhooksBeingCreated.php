<?php

namespace LaravelRestcord\Discord\Webhooks;

use Exception;
use Illuminate\Http\RedirectResponse;
use LaravelRestcord\Discord\Webhook;

trait HandlesDiscordWebhooksBeingCreated
{
    /**
     * After creating the webhook, your user will be redirected back to a controller
     * that'll interpret the response and call this method, empowering you to control
     * what happens next.  It's a good place to save the webhook details and present
     * the user with a new screen or redirect them.
     */
    abstract public function webhookCreated(Webhook $webhook) : RedirectResponse;

    /**
     * When an error occurs during webhook creation the exception will be passed here.
     *
     * For a list of common error scenarios you should handle,
     * see LaravelRestcord\Discord\ErrorFactory
     */
    public function errored(Exception $exception)
    {
        throw $exception;
    }
}
