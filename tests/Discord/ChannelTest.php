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
            'id'        => $id = time()+rand(1, 400),
            'guild_id'  => $guildId = time()+rand(10, 4000),
            'name'      => $name = uniqid(),
            'position'  => $position = time()+rand(100, 40000),
            'topic'     => $topic = uniqid(),
        ], $this->api);

        $this->assertEquals($id, $channel->id());
        $this->assertEquals($guildId, $channel->guildId());
        $this->assertEquals($name, $channel->name());
        $this->assertEquals($position, $channel->position());
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

    /** @test */
    public function getChannelsById()
    {
        $channel = new Channel([], $this->api);
        $id = time();
        $channelData = [
            'name' => $name = uniqid(),
        ];

        $this->api->shouldReceive('get')->with('/channels/'.$id)->andReturn($channelData);

        $obtainedChannel = $channel->getById($id);

        $this->assertEquals($name, $obtainedChannel->name());
    }
}
