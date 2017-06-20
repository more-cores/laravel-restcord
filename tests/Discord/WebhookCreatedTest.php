<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Channel;
use LaravelRestcord\Discord\Webhook;
use LaravelRestcord\Discord\WebhookCreated;
use PHPUnit\Framework\TestCase;
use Mockery;

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
