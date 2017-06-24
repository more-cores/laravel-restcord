<?php

namespace LaravelRestcord\Discord\Webhooks;

use Illuminate\Http\RedirectResponse;
use LaravelRestcord\Discord\Webhook;
use Exception;
use PHPUnit\Framework\TestCase;

class HandlesDiscordWebhooksBeingCreatedTest extends TestCase
{
    /** @var WebhookHandlerStub */
    protected $stub;

    public function setUp()
    {
        parent::setUp();

        $this->stub = new WebhookHandlerStub();
    }

    /** @test */
    public function throwsExceptions()
    {
        $exception = new Exception();

        $this->expectException(Exception::class);

        $this->stub->errored($exception);
    }
}

class WebhookHandlerStub
{
    use HandlesDiscordWebhooksBeingCreated;

    /**
     * After creating the webhook, your user will be redirected back to a controller
     * that'll interpret the response and call this method, empowering you to control
     * what happens next.  It's a good place to save the webhook details and present
     * the user with a new screen or redirect them.
     */
    public function webhookCreated(Webhook $webhook): RedirectResponse
    {

    }
}