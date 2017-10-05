<?php

namespace LaravelRestcord\Authentication;

use Illuminate\Contracts\Session\Session;
use LaravelRestcord\Authentication\Socialite\DiscordProvider;
use Mockery;
use PHPUnit\Framework\TestCase;

class AddTokenToSessionTest extends TestCase
{
    /** @var Mockery\MockInterface */
    protected $session;

    /** @var AddTokenToSession */
    protected $eventHandler;

    public function setUp()
    {
        parent::setUp();

        $this->session = Mockery::mock(Session::class);
        $this->eventHandler = new AddTokenToSession($this->session);
    }

    /** @test */
    public function doesntAddTokenToSession()
    {
        DiscordProvider::$token = null;

        $this->eventHandler->handle();

        // assertion is performed by Mockery, this is just to avoid this test from being risky
        $this->assertTrue(true);
    }

    /** @test */
    public function setsDiscordToken()
    {
        DiscordProvider::$token = uniqid();

        $this->session->shouldReceive('put')->with('discord_token', DiscordProvider::$token)->once();

        $this->eventHandler->handle();

        // assertion is performed by Mockery, this is just to avoid this test from being risky
        $this->assertTrue(true);
    }
}
