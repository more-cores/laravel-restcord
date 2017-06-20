<?php

namespace LaravelRestcord\Discord;

class WebhookCreated
{
    /** @var Webhook */
    protected $webhook;

    public function setWebhook(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function webhook() : Webhook
    {
        return $this->webhook;
    }
}