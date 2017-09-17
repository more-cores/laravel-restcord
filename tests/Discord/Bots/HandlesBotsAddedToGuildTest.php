<?php

namespace LaravelRestcord\Discord\Webhooks;

use Exception;
use Illuminate\Http\RedirectResponse;
use LaravelRestcord\Discord;
use LaravelRestcord\Discord\Bots\HandlesBotAddedToGuild;
use LaravelRestcord\Discord\Webhook;
use PHPUnit\Framework\TestCase;

class HandlesBotsAddedToGuildTest extends TestCase
{
    /** @var WebhookHandlerStub */
    protected $stub;

    public function setUp()
    {
        parent::setUp();

        $this->stub = new BotAddToGuildStub();
    }

    /** @test */
    public function throwsExceptions()
    {
        $exception = new Exception();

        $this->expectException(Exception::class);

        $this->stub->errored($exception);
    }
}

class BotAddToGuildStub
{
    use HandlesBotAddedToGuild;

    /**
     * After adding the bot to a guild, your user will be redirected back to a controller
     * that'll interpret the response and call this method, empowering you to control
     * what happens next.
     */
    public function botAdded(Discord\Guild $Guild): RedirectResponse
    {

    }
}
