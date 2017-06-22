<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Channel;
use Mockery;
use PHPUnit\Framework\TestCase;

class ChannelTest extends TestCase
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
        $channel = new Channel([
            'id'        => $id = time(),
            'guild_id'  => $guildId = time(),
            'name'      => $name = uniqid(),
            'topic'     => $topic = uniqid(),
        ], $this->api);

        $this->assertEquals($id, $channel->id());
        $this->assertEquals($guildId, $channel->guildId());
        $this->assertEquals($name, $channel->name());
        $this->assertEquals($topic, $channel->topic());
    }

    /** @test */
    public function identifiesVoiceChannels()
    {
        $channel = new Channel([
            'type' => 'voice',
        ], $this->api);

        $this->assertFalse($channel->isText());
        $this->assertTrue($channel->isVoice());
    }

    /** @test */
    public function identifiesTextChannels()
    {
        $channel = new Channel([
            'type' => 'text',
        ], $this->api);

        $this->assertTrue($channel->isText());
        $this->assertFalse($channel->isVoice());
    }
}
