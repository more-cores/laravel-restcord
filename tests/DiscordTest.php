<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Guild;
use Mockery;
use PHPUnit\Framework\TestCase;

class DiscordTest extends TestCase
{
    /** @var Discord */
    protected $discord;

    /** @var Mockery\MockInterface */
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->api = Mockery::mock(ApiClient::class);
        $this->discord = new Discord($this->api);
    }

    /** @test */
    public function listsCurrentUserGuilds()
    {
        $this->api->shouldReceive('get')->with('/users/@me/guilds')->andReturn([[
            'id'    => $id = time(),
            'name'  => $name = uniqid(),
        ]]);

        $guilds = $this->discord->guilds();

        $this->assertCount(1, $guilds);
        $this->assertInstanceOf(Guild::class, $guilds[0]);

        $this->assertEquals($id, $guilds[0]->id());
        $this->assertEquals($name, $guilds[0]->name());
    }

    /** @test */
    public function canHandleClient()
    {
        $this->discord = new Discord();

        Discord::setClient($this->api);

        $this->assertEquals($this->api, Discord::client());
    }

    /** @test */
    public function canSetKey()
    {
        $key = uniqid();

        Discord::setKey($key);

        $this->assertEquals($key, Discord::key());
    }

    /** @test */
    public function canSetCallbackUrl()
    {
        $callbackUrl = uniqid();

        Discord::setCallbackUrl($callbackUrl);

        $this->assertEquals($callbackUrl.'/discord', Discord::callbackUrl());

        # reset callback url
        Discord::setCallbackUrl('');
    }
}
