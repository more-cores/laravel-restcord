<?php

namespace LaravelRestcord;

use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Guild;
use Mockery;
use PHPUnit\Framework\TestCase;

class GuildTest extends TestCase
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
        $guild = new Guild([
            'id'    => $id = time(),
            'name'  => $name = uniqid(),
            'icon'  => $icon = uniqid(),
        ], $this->api);

        $this->assertEquals($id, $guild->id());
        $this->assertEquals($name, $guild->name());
        $this->assertEquals('https://cdn.discordapp.com/icons/'.$guild->id().'/'.$icon.'.jpg', $guild->iconUrl());
        $this->assertTrue($guild->hasIcon());
    }

    /** @test */
    public function recognizesWhenIconIsMissing()
    {
        $guild = new Guild([], $this->api);

        $this->assertFalse($guild->hasIcon());
    }
}
