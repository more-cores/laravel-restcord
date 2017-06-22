<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Webhook;
use Mockery;
use PHPUnit\Framework\TestCase;

class WebhookTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->api = Mockery::mock(ApiClient::class);
    }

    /** @test */
    public function getsAndSetsProperties()
    {
        $guild = new Webhook([
            'id'            => $id = time(),
            'guild_id'      => $guildId = time() + rand(400, 600),
            'channel_id'    => $channelId = time() + rand(200, 399),
            'name'          => $name = uniqid(),
            'token'         => $token = time() + rand(1, 199),
        ], $this->api);

        $this->assertEquals($id, $guild->id());
        $this->assertEquals($guildId, $guild->guildId());
        $this->assertEquals($channelId, $guild->channelId());
        $this->assertEquals($name, $guild->name());
        $this->assertEquals($token, $guild->token());
    }
}
