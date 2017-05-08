<?php

namespace LaravelRestcord;

use GuzzleHttp\Client;
use LaravelRestcord\Discord\ApiClient;
use LaravelRestcord\Discord\Guild;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class GuildTest extends TestCase
{
    /** @test */
    public function getsAndSetsProperties()
    {
        $guild = new Guild([
            'id'    => $id = time(),
            'name'  => $name = uniqid(),
            'icon'  => $icon = uniqid(),
        ]);

        $this->assertEquals($id, $guild->id());
        $this->assertEquals($name, $guild->name());
        $this->assertEquals($icon, $guild->icon());
    }
}
