<?php

namespace LaravelRestcord\Discord;

use Illuminate\Http\Response;

trait HandlesDiscordWebhooksBeingCreated
{
    /**
     * After creating the webhook, your user will be redirected back to a controller
     * that'll interpret the response and call this method, empowering you to control
     * what happens next.  It's a good place to save the webhook details and present
     * the user with a new screen or redirect them.
     */
    abstract public function webhookCreated(Webhook $webhook) : Response;
}
