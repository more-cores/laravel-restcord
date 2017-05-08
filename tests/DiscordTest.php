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
        $this->api->shouldReceive('get')->with('https://discordapp.com/api/users/@me/guilds')->andReturn([[
            'id'    => $id = time(),
            'name'  => $name = uniqid(),
        ]]);

        $guilds = $this->discord->guilds();

        $this->assertCount(1, $guilds);
        $this->assertInstanceOf(Guild::class, $guilds[0]);

        $this->assertEquals($id, $guilds[0]->id());
        $this->assertEquals($name, $guilds[0]->name());
    }
}
