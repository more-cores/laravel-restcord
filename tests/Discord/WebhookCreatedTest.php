<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\Webhook;
use LaravelRestcord\Discord\WebhookCreated;
use PHPUnit\Framework\TestCase;

class WebhookCreatedTest extends TestCase
{
    /** @test */
    public function getsAndSetsProperties()
    {
        $webhook = new Webhook();

        $webhookCreated = new WebhookCreated();
        $webhookCreated->setWebhook($webhook);

        $this->assertEquals($webhook, $webhookCreated->webhook());
    }
}
